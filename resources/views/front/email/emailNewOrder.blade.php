<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Letter</title>
</head>
<body>

<table style="max-width: 1000px; height: auto; max-height: 100000000000px; border-spacing: 0; border-collapse: collapse; font-family: Arial,sans-serif; background-color: #197124; text-align: center; margin: 0 auto; padding: 0; min-width:900px;" bgcolor="#fedc29">
    <tbody>
    <tr>
        <td style="width: 100%; padding: 10px 10px 0;">

            <table style="width: 100%; height: auto; max-height: 100000000000px; background-color: #fedc29; border-spacing: 0; border-collapse: collapse; font-family: Arial,sans-serif; margin: 0 auto;">
                <tbody>

                <tr style="height: auto; background-color: white; padding: 55px 0;" bgcolor="white">
                    <td style="width: 100%; padding: 30px 5px;">
                        <a href="{{ url('/', $lang) }}" target="_blank"><img src="{{asset('front-assets/img/logo/logo.png')}}" alt="Agro-tehnica.md Logotype" style="padding-bottom: 10px; margin: 0 auto;"></a><br>
                        <span style="width: 100%; color: #000000; font-size: 24px;font-weight: bold;">{{ ShowLabelById(181, $lang_id) }}</span><br>

                        @if($if_admin == 1)
                            <span style="width: 100%; color: #000; font-size: 18px;"><span style="font-weight: bold">{{ ShowLabelById(170, $lang_id) }}:</span> {{ $orders['id'] ?? '' }}</span><br>
                            <span style="width: 100%; color: #000; font-size: 18px;"><span style="font-weight: bold">{{ ShowLabelById(171, $lang_id) }}:</span> {{ $orders_user['name'] ?? '' }}</span><br>
                            <span style="width: 100%; color: #000; font-size: 18px;"><span style="font-weight: bold">{{ ShowLabelById(172, $lang_id) }}:</span> {{ $orders_user['phone'] ?? '' }}</span><br>
                            <span style="width: 100%; color: #000; font-size: 18px;"><span style="font-weight: bold">{{ ShowLabelById(173, $lang_id) }}:</span> {{ $orders_user['email'] ?? '' }}</span><br>
                            <span style="width: 100%; color: #000; font-size: 18px;"><span style="font-weight: bold">{{ ShowLabelById(174, $lang_id) }}:</span> {{ $orders_user['address'] ?? '' }}</span><br>
                           {{-- <span style="width: 100%; color: #000; font-size: 18px;"><span style="font-weight: bold">{{ ShowLabelById(49, $lang_id) }}:</span> {{ $orders_user['city'] ?? '' }}</span><br>
                            <span style="width: 100%; color: #000; font-size: 18px;"><span style="font-weight: bold">{{ ShowLabelById(69, $lang_id) }}:</span> {{ $orders_user['descr'] ?? ShowLabelById(68, $lang_id) }}</span>--}}<br>
                        @endif
                    </td>
                </tr>

                @if($if_admin == 0)
                    <tr style="height: auto; background-color: white; padding: 55px 0;" bgcolor="white">
                        <td>
                            <p style="width: 70%;margin: 0 auto 0 auto;font-size: 18px; color: #000;">{{ ShowLabelById(170, $lang_id) }} - {{ $orders['id'] ?? '' }}.</p>
                            <p style="width: 70%;margin: 0 auto 0 auto;font-size: 17px; color: #000;">{{ ShowLabelById(171, $lang_id) }}</p>
                            <p style="width: 70%; margin: 10px auto;font-size: 17px; color: #000;">{{ ShowLabelById(172, $lang_id) }}</p>
                            <p style="width: 70%;margin: 10px auto 20px auto;font-size: 17px; color: #000;">{{ ShowLabelById(173, $lang_id) }}</p>
                            <p style="width: 70%;margin: 10px auto 20px auto;font-size: 17px; color: #000;">{{ ShowLabelById(174, $lang_id) }}</p>
                        </td>
                    </tr>
                @endif

                <tr style="background-color: #fff; height: auto; max-height: 100000000000px;" bgcolor="#fff">
                    <td style="width: 100%; padding-bottom: 40px;">
                        <table style="width: 90%; border-spacing: 0; border-collapse: collapse; margin: 0 auto;">
                            <tbody>
                            <tr>
                                <td style="background-color: #fff; padding: 20px 10px; border: 1px solid #d3d3d3; color: #1d2b4d;" bgcolor="#fff"><span style="font-size: 18px; color: #000000;">N:</span></td>
                                <td style="width: 10%; background-color: #fff; padding: 20px 2px; border: 1px solid #d3d3d3; color: #1d2b4d;" bgcolor="#fff"><span style="font-size: 18px; color: #000000;">{{ ShowLabelById(175, $lang_id) }}:</span></td>
                                <td style="width: 50%; background-color: #fff; padding: 20px 10px; border: 1px solid #d3d3d3; color: #1d2b4d;" bgcolor="#fff"><span style="font-size: 18px; color: #000000;">{{ ShowLabelById(176, $lang_id) }}:</span></td>
                                <td style="width: 10%; background-color: #fff; padding: 20px 5px; border: 1px solid #d3d3d3; color: #1d2b4d;" bgcolor="#fff"><span style="font-size: 18px; color: #000000;">{{ ShowLabelById(121, $lang_id) }}:</span></td>
                                <td style="width: 60%; background-color: #fff; padding: 20px 5px; border: 1px solid #d3d3d3; color: #1d2b4d;" bgcolor="#fff"><span style="font-size: 18px; color: #000000;">{{ ShowLabelById(177, $lang_id) }}:</span></td>
                                <td style="width: 60%; background-color: #fff; padding: 20px 5px; border: 1px solid #d3d3d3;" bgcolor="#fff"><span style="font-size: 18px; color: #000000;">{{ ShowLabelById(178, $lang_id) }}:</span></td>
                            </tr>
                            @if(!empty($basket) && count($basket))
                                @foreach($basket as $one_item)
                                    <tr>
                                        <td style="background-color: #fff; padding: 20px 10px; border: 1px solid #d3d3d3;" bgcolor="#fff"><span style="font-size: 18px; color: black;">{{ $loop->iteration }}</span></td>
                                        <td style="width: 25%; background-color: #fff; padding: 20px 10px; border: 1px solid #d3d3d3;" bgcolor="#fff">
                                            <img src="{{ asset($one_item->oImage && $one_item->oImage->img? 'upfiles/gallery/s/'.$one_item->oImage->img : 'front-assets/img/no-image.png') }}" alt="{{ $one_item->name ?? '' }}" height="90px"/>
                                        </td>
                                        <td style="width: 20%; background-color: #fff; padding: 20px 2px; border: 1px solid #d3d3d3; color: #1d2b4d;" bgcolor="#fff"><span style="font-size: 18px; color: #000000;">{{ $one_item->goods_name ?? '' }}</span></td>
                                        <td style="width: 20%; background-color: #fff; padding: 20px 10px; border: 1px solid #d3d3d3; color: #1d2b4d;" bgcolor="#fff"><span style="font-size: 18px; color: #000000;">{{ $one_item->items_count ?? '' }}</span></td>
                                        <td style="width: 60%; background-color: #fff; padding: 20px 5px; border: 1px solid #d3d3d3; color: #1d2b4d;" bgcolor="#fff"><span style="font-size: 18px; color: #000000;"> {{ getDefaultPriceFormat($one_item->goods_price) }} {{ ShowLabelById(76, $lang_id) }}</span></td>
                                        <td style="width: 60%; background-color: #fff; padding: 20px 5px; border: 1px solid #d3d3d3; color: #1d2b4d;" bgcolor="#fff"><span style="font-size: 18px; color: #000000;"> {{ getDefaultPriceFormat($one_item->goods_price * $one_item->items_count) }} {{ ShowLabelById(76, $lang_id) }}</span></td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        {{--@if($orders_data->delivery_cost && $orders_data->delivery_cost > 0)
                            <h2 style="margin: 20px 0; color: #000000; text-align: right; margin-right: 30px;">{{ ShowLabelById(20, $lang_id) }}: {{ $orders_data->delivery_cost ?? '' }} {{ strtoupper(ShowLabelById(7, $lang_id)) }}</h2>
                            <h2 style="margin: 20px 0; color: #000000; text-align: right; margin-right: 30px;">{{ ShowLabelById(54, $lang_id) }}: {{ getDefaultPriceFormat($total_price+$orders_data->delivery_cost) }} {{ strtoupper(ShowLabelById(7, $lang_id)) }}</h2>
                        @else--}}
                        <h2 style="margin: 20px 0; color: #000000; text-align: right; margin-right: 30px;">{{ ShowLabelById(129, $lang_id) }}: {{ getDefaultPriceFormat($total_price) }} {{ ShowLabelById(79  , $lang_id) }}</h2>
                        {{--@endif--}}
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td style="width: 100%; min-height: 60px; max-height: 100000000000px; padding: 5px; background-color: #197124;">
            @if($if_admin == 1)
                <span style="color: #ffffff; font-weight: bold">{{ ShowLabelById(179,$lang_id) }}: </span>
                <span style="color: #ffffff;"><a href="{{url($lang, ['back', 'orders', 'pickup', 'edititem', $orders['id']])}}" style="color: #ffffff;">{{url($lang, ['back', 'orders', 'pickup', 'edititem', $orders['id']])}}</a></span>
            @endif
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>
