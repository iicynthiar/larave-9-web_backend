<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('歡迎登入後端管理系統') }}
        </h2>

        <ul class="list-group">
            <li class="list-group-item"><a href="/manageNews/manageNews">最新消息</a></li>
            <li class="list-group-item"><a href="/manageActivity/manageActivity">活動管理</a></li>
            <li class="list-group-item"><a href="/manageContacts">聯絡我們</a></li>
            <li class="list-group-item"><a href="/manageMember/manageMembers">會員管理</a></li>
        </ul>
    </x-slot>

<style type="text/css">
</style>
    <!-- <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <x-welcome />
            </div>
        </div>
    </div> -->
</x-app-layout>
