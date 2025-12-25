@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="w-full max-w-md bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-semibold mb-4 text-center">Definir nueva contraseña</h1>

        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.reset.handle') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1" for="password">Nueva contraseña</label>
                <input id="password" name="password" type="password" required
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="password_confirmation">Confirmar contraseña</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
            </div>

            <div class="pt-2">
                <button type="submit"
                    class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700 transition">
                    Guardar contraseña
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
