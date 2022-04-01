@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="list-page">

        @include('admin.list-elements', [
        'actions' => [
            trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
            trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'ordersCart/orderscart')
        ]
    ])


        <div class="list-table">
            @if(!$orders->isEmpty())
                <table class="table" empty-response="{{trans('variables.list_is_empty')}}">
                    <thead>
                    <tr>
                        <th>{{trans('variables.title_table')}}</th>
                        <th class="restore-all">{{trans('variables.reestablish_table')}}</th>
                        @if($groupSubRelations->del_from_rec == 1)
                            <th class="remove-all">{{trans('variables.delete_table')}}</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $one_order)
                        <tr id="{{$one_order->id}}">
                            <td>
                                <span>{{$one_order->ordersUsers->name ?? ''}}</span>
                            </td>
                            <td class="medium check-restore-element">
                                <input type="checkbox" class="restore-all-elements"
                                       name="restore_elements[{{$one_order->id}}]" value="{{$one_order->id}}"
                                       url="{{urlForFunctionLanguage($lang, 'ss/restoreOrder')}}">
                            </td>
                            @if($groupSubRelations->del_from_rec == 1)
                                <td class="medium check-destroy-element">
                                    <input type="checkbox" class="remove-all-elements"
                                           name="destroy_elements[{{$one_order->id}}]" value="{{$one_order->id}}"
                                           url="{{urlForFunctionLanguage($lang, 'ss/destroyOrderFromCart')}}">
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    <tfoot>
                    <tr>
                        <td colspan="10"></td>
                    </tr>
                    </tfoot>
                </table>
            @else
                <div class="empty-response">{{trans('variables.list_is_empty')}}</div>
            @endif
        </div>
        <div id="loader-gif" class="loader-list"></div>
    </div>

@stop