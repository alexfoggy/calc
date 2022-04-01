@extends('front.front-app')

@section('meta-tags')
    @include('front.meta-tags.for-other-pages')
@stop

@include('front.header')

@section('container')


    <section class="table-page">
        <form action="{{url($lang,'createTable')}}" method="post" id="table_create" enctype="multipart/form-data">
            <div class="container">
                <h1><input type="text" name="group" placeholder="Класс|Группа"></h1>
                <h2 class="row">
                    <div class="col-lg-3">
                        <input type="text" name="study_place" placeholder="Школа|Универ">

                    </div>
                    <div class="col-lg-3"><input type="text" name="city" placeholder="Город"></div>
                </h2>
                <input type="hidden" name="row_count" value="6" class="count_rows_status"></h2>

                <div class="table">

                    <div class="overflow">
                        <table>
                            <tr>
                                <th>№</th>
                                <th>День/Время</th>
                                <th>Понедельник</th>
                                <th>Вторник</th>
                                <th>Среда</th>
                                <th>Четверг</th>
                                <th>Пятница</th>
                                <th>Суббота</th>
                            </tr>
                            <tr class='row-num-1 active' data-n='1'>
                                <th class='number-table'>1</th>
                                <th>С <span><input type="text" name="p[]" placeholder="8:00"></span> до
                                    <span><input type="text" name="p[]" placeholder="9:00"></span></th>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                            </tr>
                            <tr class='row-num-2 active' data-n='2'>
                                <th class='number-table'>2</th>
                                <th>С <span><input type="text" name="p[]" placeholder="9:00"></span> до <span><input
                                                type="text" name="p[]" placeholder="9:00"></span></th>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                            </tr>
                            <tr class='row-num-3 active' data-n='3'>
                                <th class='number-table'>3</th>
                                <th>С <span><input type="text" name="p[]" placeholder="9:00"></span> до <span><input
                                                type="text" name="p[]" placeholder="9:00"></span></th>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                            </tr>
                            <tr class='row-num-4 active' data-n='4'>
                                <th class='number-table'>4</th>
                                <th>С <span><input type="text" name="p[]" placeholder="9:00"></span> до <span><input
                                                type="text" name="p[]" placeholder="9:00"></span></th>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                            </tr>
                            <tr class='row-num-5 active' data-n='5'>
                                <th class='number-table'>5</th>
                                <th>С <span><input type="text" name="p[]" placeholder="9:00"></span> до <span><input
                                                type="text" name="p[]" placeholder="9:00"></span></th>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                            </tr>
                            <tr class='row-num-6 active' data-n='6'>
                                <th class='number-table'>6</th>
                                <th>С <span><input type="text" name="p[]" placeholder="9:00"></span> до <span><input
                                                type="text" name="p[]" placeholder="9:00"></span></th>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                            </tr>
                            <tr class='row-num-7' data-n='7'>
                                <th class='number-table'>7</th>
                                <th>С <span><input type="text" name="p[]" placeholder="9:00"></span> до <span><input
                                                type="text" name="p[]" placeholder="9:00"></span></th>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                            </tr>
                            <tr class='row-num-8' data-n='8'>
                                <th class='number-table'>8</th>
                                <th>С <span><input type="text" name="p[]" placeholder="9:00"></span> до <span><input
                                                type="text" name="p[]" placeholder="9:00"></span></th>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                                <td><textarea name="p[]"> </textarea></td>
                            </tr>

                        </table>
                        <div class="d-flex justify-content-between align-items-center lesson-nav">
                            <span class='del-lesson'>Убрать урок</span>
                            <span class="add-lesson">Добавить урок</span>
                        </div>
                    </div>
                </div>
                <div class="shorts">
                    <h3>События</h3>
                    <input type="text" placeholder="Название события" name="head_shorts">
                    <textarea name="body_shorts"></textarea>
                </div>
                <div class="center">
                    <button class="mb-5 w-20 btn-go" data-form-id="table_create" type="submit" onclick="saveForm(this)">
                        Создать
                    </button>
            </div>
        </form>
    </section>


@stop

@push('timepicker')
    <script src="{{asset('front-assets/js/timepicker.min.js')}}"></script>

    <script>
        $('.table input').timepicker();
    </script>

    @endpush
@include('front.footer')
