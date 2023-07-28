@extends('layout.layout')
@include('layout.header')

<div class="relative overflow-x-auto flex justify-center h-screen">
    <div class="w-3/4 h-3/4 bg-slate-100 justify-center flex">
        <div class="bg-white w-1/2 h-3/6 mt-5 px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-gray-600">
            Withdraw Money
            <hr class="mt-2">
            @if (session('error'))
                <span class="err">*{{ session('error') }}</span>
            @endif
            <form action="withdraw" method="POST">
                @csrf
                <div class="mb-6">
                    <label for="withdraw"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-600 mt-2">Amount</label>
                    <input type="number" name="amount" id="base-input"
                        class="bg-white-50 border border-white-300 text-gray-900 text-sm rounded-lg focus:ring-black-500 focus:border-black-500 block w-full p-2.5 dark:bg-white-700 dark:border-white-600 dark:placeholder-gray-400 dark:text-gray dark:focus:ring-black-500 dark:focus:border-black-500">
                    @error('amount')
                        <span class="err">*{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <button type="submit"
                        class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Withdraw</button>
                </div>
            </form>
        </div>
    </div>
</div>
