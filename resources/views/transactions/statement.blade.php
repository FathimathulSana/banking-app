@extends('layout.layout')
@include('layout.header')

<div class="relative overflow-x-auto flex justify-center h-screen">
    <div class="w-3/4 h-3/4 bg-slate-100 justify-center flex">
    <table class="w-1/3 h-1/5 mt-5 text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase dark:text-gray-500">
            <tr >
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-gray-600">
                    Statement of Account
                </th>
            </tr>
            
            <tr class=" bg-gray-50 dark:bg-white">
                <th scope="col" class="px-6 py-3">
                    #
                </th>
                <th scope="col" class="px-6 py-3">
                   DATE&TIME
                </th>
                <th scope="col" class="px-6 py-3">
                    AMOUNT
                </th>
                <th scope="col" class="px-6 py-3">
                    TYPE
                </th>
                <th scope="col" class="px-6 py-3">
                    DETAILS
                </th>
                <th scope="col" class="px-6 py-3">
                    BALANCE
                </th>
            </tr>
        </thead>
        <tbody>
            <tr class="bg-white border-b dark:bg-white dark:border-gray-200">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-gray-500">
                    1
                </th>
                <td class="px-6 py-4">
                    Silver
                </td>
                <td class="px-6 py-4">
                    Laptop
                </td>
                <td class="px-6 py-4">
                    $2999
                </td>
                <td class="px-6 py-4">
                    $2999
                </td>
                <td class="px-6 py-4">
                    $2999
                </td>
            </tr>
            
        </tbody>
    </table>
</div>
