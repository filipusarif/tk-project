<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Pembayaran;
use App\Models\Siswa;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Midtrans Configuration
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$clientKey = env('MIDTRANS_CLIENT_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function showPayment()
    {
        $siswas = Siswa::with('pembayaran')
                    ->where('status_verifikasi', 'verified')
                    ->where('user_id', auth()->id())
                    ->first();
    
        if (!$siswas) {
            return view('pendaftaran.informasi-not');
        }
    

        $pendingPayments = Pembayaran::where('siswa_id', $siswas->id)
                                     ->where('status', 'pending')
                                     ->get();
    
        
        $totalAmount = $pendingPayments->sum('jumlah');
    
      
        if ($totalAmount <= 0) {
            return view('pendaftaran.informasi', [
                'siswas' => $siswas,
                'pendingPayments' => $pendingPayments,
                'totalAmount' => $totalAmount,
                'paidPayments' => Pembayaran::where('siswa_id', $siswas->id)
                                            ->where('status', 'paid')
                                            ->get(),
                'snapToken' => null, 
            ])->with('info', 'Tidak ada pembayaran yang perlu diselesaikan.');
        }
    

        $transaction_details = [
            'order_id' => uniqid('order-'), 
            'gross_amount' => $totalAmount,
        ];
    
        $items = [];
        foreach ($pendingPayments as $payment) {
            $items[] = [
                'id' => $payment->id,
                'price' => $payment->jumlah,
                'quantity' => 1,
                'name' => 'Pembayaran Siswa ' . $siswas->nama_lengkap,
            ];
        }
    
        $transaction_data = [
            'transaction_details' => $transaction_details,
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'phone' => auth()->user()->phone ?? '08123456789',
            ],
        ];
    
        $paidPayments = Pembayaran::where('siswa_id', $siswas->id)
            ->where('status', 'paid')
            ->get();
    
        $snapToken = Snap::getSnapToken($transaction_data);
    
        return view('pendaftaran.informasi', compact('siswas', 'pendingPayments', 'totalAmount', 'paidPayments', 'snapToken'));
    }
    

    public function createPayment(Request $request)
    {
        $siswas = Siswa::with('pembayaran')->where('status_verifikasi', 'verified')
                    ->where('user_id', auth()->id())
                    ->first();


        $pendingPayments = Pembayaran::where('siswa_id', $siswas->id)
                                     ->where('status', 'pending')
                                     ->get();

       
        $totalAmount = $pendingPayments->sum('jumlah');

        
        $transaction_details = [
            'order_id' => 'ORD-' . time(),
            'gross_amount' => $totalAmount,
        ];

        $items = [];
        foreach ($pendingPayments as $payment) {
            $items[] = [
                'id' => $payment->id,
                'price' => $payment->jumlah,
                'quantity' => 1,
                'name' => 'Pembayaran Siswa ' . $siswas->nama_lengkap,
            ];
        }

        $transaction_data = [
            'transaction_details' => $transaction_details,
            'item_details' => $items,
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ],
        ];

        
        try {
            $snapToken = Snap::getSnapToken($transaction_data);
            return view('pendaftaran.informasi', compact('snapToken'));
        } catch (\Exception $e) {
            return redirect()->route('informasi')->withErrors('Payment failed. Please try again.');
        }
    }

    public function handlePaymentCallback(Request $request)
    {
        $payment_status = $request->status_code; 
        $order_id = $request->order_id;
        $siswa_id = $request->siswa_id;

        $payment = Pembayaran::where('siswa_id', $siswa_id)->first();

        if ($payment) {
            if ($payment_status == '200') {
                Pembayaran::where('siswa_id', $siswa_id)
                ->update([
                    'status' => 'paid',           
                    'tanggal_bayar' => now(),     
                ]);
                return redirect()->route('informasi')->with('success', 'Payment Successful');
            } else {
                return redirect()->route('informasi')->withErrors('Payment failed or canceled');
            }
        }

        return redirect()->route('informasi')->withErrors('Invalid payment request');
    }
}
