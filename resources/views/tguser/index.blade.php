@extends('layouts.app')
@section('content')
    <div class="panel panel-default">
        <div style="display: flex; justify-content: space-between" class="panel-heading">
            <h2>Telegram-bot users</h2>  
        </div>
        <div class="panel-body">
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th>Username</th>
                    <th>Chat_id</th>
                    <th>Isvip</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($tguser as $item)
                    <tr>
                        <td>{{ $item->username }}</td>
                        <td>{{ $item->user_id }}</td>
                        <td>{{ $item->isvip }}</td>
                        <td style="text-align:right;">
                            <a href="/tguser/{{ $item->id }}/edit" class="btn btn-primary">Set true vip status</a>
                        </td>
                       
                       
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

<style>
    table {
        width: 90%;
        margin-top: 20px;
    }
    table, th, td {
      border: 1px solid grey !important;
    }
    thead {
      position: sticky;
      top: 0;
      background: #2B2F33;
      color: white;
      border-color:white;
    }
    .wrap {
      margin: 0 !important;
      padding: 0 !important;
    }
    main {
        width: 100%;
        display:flex;
        flex-direction: column;
        align-items:center;
    }
    .panel {
        width: 95%;
    }
  </style>