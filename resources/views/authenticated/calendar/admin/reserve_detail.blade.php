@extends('layouts.sidebar')

@section('content')
<div class="vh-100 d-flex" style="align-items:center; justify-content:center;">
  <div class="w-50 m-auto h-75">
    <p><span>{{ $date }}日</span><span class="ml-3">{{ $part }}部</span></p>
    <div class="h-75 border">
      <table class="reserve_detail">
        <tr class="text-center">
          <th>ID</th>
          <th>名前</th>
          <th>場所</th>
        </tr>

        @foreach($reservePersons as $reservePerson)
        @foreach($reservePerson-> users as $user)
        <tr class="text-center">
          <td class="w-25">{{ $user->id }}</td>
          <td class="w-25">{{ $user->over_name }}{{ $user->under_name }}</td>
          <td class="w-25">リモート</td>
        </tr>
        @endforeach
        @endforeach
        <tr class="text-center">
          <td class="w-25"></td>
          <td class="w-25"></td>
        </tr>
      </table>
    </div>
  </div>
</div>
@endsection
