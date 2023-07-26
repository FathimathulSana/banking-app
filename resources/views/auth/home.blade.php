@extends('layout.layout')
@include('layout.header')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<hr>
<div class="relative overflow-x-auto flex justify-center h-screen">
    <div class="w-3/4 h-3/4 bg-slate-100 justify-center flex">
        <table class="w-1/3 h-1/5 mt-5 text-sm text-left text-gray-500 dark:text-gray-400">
            @if (isset($userData))
                <tbody>
                    <tr>
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-gray-600">
                            Welcome {{ $userData->name }}
                        </th>
                    </tr>
                    <tr class="bg-white border-b dark:bg-white-800 dark:border-white-700">
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-gray-400">
                            Your ID
                        </th>
                        <td class="px-6 py-4">
                            {{ $userData->email }}
                        </td>
                    </tr>
                    <tr class="bg-white border-b dark:bg-white-800 dark:border-white-700">
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-gray-400">
                            Your Balance
                        </th>
                        @if (isset($balance))
                            <td class="px-6 py-4">
                                {{ $balance }}
                            </td>
                        @endif
                    </tr>
                </tbody>
            @endif
        </table>
    </div>
</div>
