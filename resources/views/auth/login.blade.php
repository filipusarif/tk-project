<x-base-user>
    <x-slot:title>Login</x-slot:title>

    <section class="bg-gray-50 light:bg-gray-900 font-sans">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
        <!-- <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 light:text-white">
            <img class="w-8 h-8 mr-2" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/logo.svg" alt="logo">
            Flowbite    
        </a> -->
        <div class="w-full bg-white rounded-lg shadow light:border md:mt-0 sm:max-w-md xl:p-0 light:bg-gray-800 light:border-gray-700">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl light:text-white">
                    Masuk PPDB
                </h1>
                @if(session('success'))
                    <div class="mb-4 text-sm text-green-600">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 text-sm text-red-600">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="space-y-4 md:space-y-6" action="/login" method="POST">
                    @csrf
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Email</label>
                        <input type="email" name="email" id="email" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-blue-500 light:focus:border-blue-500" 
                            placeholder="masukkan email ..." required>
                        <!-- @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror -->
                    </div>

                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Password</label>
                        <input type="password" name="password" id="password" 
                            placeholder="••••••••" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-blue-500 light:focus:border-blue-500" 
                            required>
                        <!-- @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror -->
                    </div>

                    <!-- @if ($errors->has('email'))
                        <div class="mt-4 text-sm text-red-600">
                            {{ $errors->first('email') }}
                        </div>
                    @endif -->

                    <button type="submit" class="w-full text-white bg-orange-600 hover:bg-orange-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center light:bg-orange-600 light:hover:bg-orange-700 light:focus:ring-primary-800">Sign in</button>
                    <p class="text-sm font-dark text-gray-500 light:text-gray-400">
                        Belum mempunyai akun?<a href="/register" class="font-medium text-primary-600 hover:underline light:text-primary-500">Daftar</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
    </section>

</x-base-user>