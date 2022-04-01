@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="list-page">

        @include('admin.list-elements', [
        'actions' => [
            trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
            trans('variables.edit_element') => urlForFunctionLanguage($lang,  'pickup/edititem/' . $orders->id),
            trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'ordersCart/orderscart')
        ]
    ])


        <div class="list-table">
            <div class="table-title">
                <span>Order info</span>
            </div>
            <table class="table" empty-response="{{trans('variables.list_is_empty')}}">
                <thead>
                <tr>
                    <th>№.:</th>
                    <th>{{trans('variables.order_type')}}</th>
                    <th>{{trans('variables.delivery_method')}}</th>
                    <th>{{trans('variables.pay_method')}}</th>
                    <th>{{trans('variables.total_count')}}</th>
                    <th>{{trans('variables.total_price')}}</th>
                    {{--<th>{{ trans('variables.status_text') }}</th>--}}
                    <th>{{trans('variables.date_table')}}</th>
                    @if($groupSubRelations->active == 1)
                        <th>{{trans('variables.active_table')}}</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <span>{{$orders->id ?? ''}}</span>
                    </td>
                    <td>
                        <span>{{$orders->fast_order == 1 ? 'Fast' : 'Simple'}}</span>
                    </td>
                    <td>
                        <span>{{$orders->delivery_method ?? ''}}</span>
                    </td>
                    <td>
                        <span>{{$orders->pay_method ?? ''}}</span>
                    </td>
                    <td>
                        <span>{{$orders->ordersData->total_count ?? ''}}</span>
                    </td>
                    <td>
                        <span>{{$orders->ordersData->total_price ?? ''}}</span>
                    </td>
                    {{--<td class="active-link">

                        <a href="" class="change-active{{$orders->paid == 1 ? ' active' : ''}}" title="{{$orders->paid == 1 ? trans('variables.paid') : trans('variables.non_paid') }}"
                                data-active="{{$orders->paid}}" action="paid-order" url="{{$url_for_active_elem}}"
                                element-id="{{$orders->id}}"></a>
                        <br>
                        @if($orders->paid == 1)
                            <span id="paid_text">{{ trans('variables.paid') }}</span>
                        @elseif($orders->paid ==0)
                            <span id="paid_text">{{ trans('variables.non_paid') }}</span>
                        @endif
                    </td>--}}
                    <td>
                        <span>{{$orders->created_at ?? ''}}</span>
                    </td>
                    @if($groupSubRelations->active == 1)
                        <td class="small active-link">
                            <a href="" class="change-active{{$orders->active == 1 ? ' active' : ''}}"
                               data-active="{{$orders->active}}" action="edit-order" url="{{$url_for_active_elem}}"
                               element-id="{{$orders->id}}"></a>
                        </td>
                    @endif
                </tr>
                <tfoot>
                <tr>
                    <td colspan="10"></td>
                </tr>
                </tfoot>
            </table>

            <div class="table-delimiter"></div>

            <div class="table-title">
                <span>User info</span>
            </div>
            <table class="table" empty-response="{{trans('variables.list_is_empty')}}">
                <thead>
                <tr>
                    <th>{{trans('variables.name_text')}}</th>
                    <th>{{trans('variables.email_text')}}</th>
                    <th>{{trans('variables.phone')}}</th>
                    @if($orders->ordersUsers->descr)
                        <th>{{trans('variables.comment_table')}}</th>
                    @endif
                    <th>{{trans('variables.city')}} / {{trans('variables.address')}}</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <span>{{$orders->ordersUsers->name ?? ''}}</span>
                    </td>
                    <td>
                        <span>{{$orders->ordersUsers->email ?? ''}}</span>
                    </td>
                    <td>
                        <span>{{$orders->ordersUsers->phone ?? ''}}</span>
                    </td>
                    @if($orders->ordersUsers->descr)
                        <td>
                            <span>{{$orders->ordersUsers->descr ?? ''}}</span>
                        </td>
                    @endif
                    <td>
                        <span>
                            {{ $orders->ordersUsers->city ?? trans('variables.do_not_exist') }} /
                            {{ $orders->ordersUsers->address ?? trans('variables.do_not_exist') }}
                        </span>
                    </td>

                </tr>
                <tfoot>
                <tr>
                    <td colspan="10"></td>
                </tr>
                </tfoot>
            </table>

            @if(!$orderedItems->isEmpty())
                <div class="table-delimiter"></div>

                <div class="table-title">
                    <span>Ordered items</span>
                </div>
                <table class="table" empty-response="{{trans('variables.list_is_empty')}}">
                    <thead>
                    <tr>
                        <th>{{trans('variables.name_text')}}</th>
                        <th>{{trans('variables.price')}} (1 шт.)</th>
                        <th>{{trans('variables.total_count')}}</th>
                        {{--<th>{{trans('Colors')}}</th>--}}
                        {{--<th>{{trans('Size')}}</th>--}}
                        <th>{{trans('variables.date_table')}}</th>
                    </tr>
                    </thead>

                    @foreach($basket as $basket_elem)
                        <tr id="{{$basket_elem->id ?? ''}}">
                            <td>
                                <span>{{$basket_elem->goods_name ?? ''}}</span>
                            </td>
                            <td>
                                <span>{{$basket_elem->goods_price ?? ''}}</span>
                            </td>
                            <td>
                                <span>{{$basket_elem->items_count ?? ''}}</span>
                            </td>
                            {{--<td>--}}

                            {{--<span>{{$basket_elem->colors_name ?? ''}}</span>--}}

                            {{--</td>--}}

                            {{--<td>--}}

                            {{--<span>{{$basket_elem->size_name ?? ''}}</span>--}}

                            {{--</td>--}}
                            <td>
                                <span>{{$basket_elem->created_at ?? ''}}</span>
                            </td>
                            @endforeach
                        </tr>
                        <tfoot>
                        <tr>
                            <td colspan="10"></td>
                        </tr>
                        </tfoot>
                </table>
            @endif
            {{--<div class="admin_comment">
                <h2>{{trans('variables.comment_table')}}</h2>
                <input type="hidden" name="id" value="{{ $orders->id }}">
                <textarea placeholder="{{trans('variables.comment_table')}}" name="comment">{{ $orders->admin_comment ?? '' }}</textarea>
                <input type="submit" id="edit_admin_comment" url="/ru/back/orders" value="{{trans('variables.save_it')}}">
            </div>--}}
        </div>

        <div id="loader-gif" class="loader-list"></div>
    </div>
@stop

