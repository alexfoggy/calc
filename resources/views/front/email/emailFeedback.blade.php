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

<table style="width: 600px; height: auto; max-height: 100000000000px; border-spacing: 0; border-collapse: collapse; font-family: Arial,sans-serif; background-color: #4452fb; text-align: center; margin: 0 auto; padding: 0;" bgcolor="#4452fb">
    <tbody>
    <tr>
        <td style="width: 100%; padding: 20px 20px 0;">

            <table style="width: 100%; height: auto; max-height: 100000000000px; border-spacing: 0; border-collapse: collapse; font-family: Arial,sans-serif; margin: 0 auto;">
                <tbody>

                <tr style="height: auto; background-color: #fafafa; padding: 55px 0;" bgcolor="#fafafa">
                    <td style="width: 100%; padding: 55px 5px;">
                        <img src="{{asset('front-assets/img/logo/logo.png')}}" alt="letter" style="padding-bottom: 40px; margin: 0 auto; width:100px"><br>
                        <span style="width: 100%; color:#000000; font-size: 24px;">

                        </span>
                    </td>
                </tr>
                <tr style="background-color: #fff; height: auto; max-height: 100000000000px;" bgcolor="#fff">
                    <td style="width: 100%; padding-bottom: 40px;">
                        <table style="width: 90%; border-spacing: 0; border-collapse: collapse; margin: 0 auto;">
                            <tbody>
                            <tr>
                                <td style="width: 50%; background-color: #fff; padding: 20px 10px; border: 1px solid #d3d3d3;" bgcolor="#fff"><span style="font-size: 18px; color: #101d1f;">{{ trans('variables.name_text')  }}</span></td>
                                <td style="width: 50%; background-color: #fff; padding: 20px 10px; border: 1px solid #d3d3d3;" bgcolor="#fff"><span style="font-size: 18px; color: #101d1f;">{{ $data['name'] ?? '' }}</span></td>
                            </tr>
                            <tr>
                                <td style="width: 50%; background-color: #fff; padding: 20px 10px; border: 1px solid #d3d3d3;" bgcolor="#fff"><span style="font-size: 18px; color: #101d1f;">{{ trans('variables.phone') }}</span></td>
                                <td style="width: 50%; background-color: #fff; padding: 20px 10px; border: 1px solid #d3d3d3;" bgcolor="#fff"><span style="font-size: 18px; color: #101d1f;">{{$data['phone'] ?? '' }}</span></td>
                            </tr>
                            <tr>
                                <td style="width: 50%; background-color: #fff; padding: 20px 10px; border: 1px solid #d3d3d3;" bgcolor="#fff"><span style="font-size: 18px; color: #101d1f;">{{ trans('variables.email_text') }}</span></td>
                                <td style="width: 50%; background-color: #fff; padding: 20px 10px; border: 1px solid #d3d3d3;" bgcolor="#fff"><span style="font-size: 18px; color: #101d1f;">{{$data['email'] ?? '' }}</span></td>
                            </tr>
                                <tr>
                                    <td style="width: 50%; background-color: #fff; padding: 20px 10px; border: 1px solid #d3d3d3;" bgcolor="#fff"><span style="font-size: 18px; color: #101d1f;">{{ trans('variables.subject_text') }}</span></td>
                                    <td style="width: 50%; background-color: #fff; padding: 20px 10px; border: 1px solid #d3d3d3;" bgcolor="#fff"><span style="font-size: 18px; color: #101d1f;">{{$data['subject'] ?? '' }}</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td style="width: 100%; min-height: 60px; max-height: 100000000000px; background-color: #4452fb; padding: 5px;" bgcolor="#4452fb">
            <span style="color: #ffffff;">{{ ShowLabelById(179,$lang_id) }}:</span>
            <span style="color: #333333;"><a href="{{url($lang, ['back', 'feedform', Str::slug($data['name']), 'edititem', $data['id']])}}" style="color: #fff;">{{url($lang, ['back', 'feedform', Str::slug($data['name']), 'edititem', $data['id']])}}</a></span>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>