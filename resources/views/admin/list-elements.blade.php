<div class="list-head">
    @if(isset($actions) && count($actions))
        @if(isset($search) && $search == 'true')
            <div class="list-head-tabs">
                <a href="" class="search-btn"></a>
            </div>
        @endif
        @foreach($actions as $action_name => $action)
            <div class="list-head-tabs{{$action == url()->current() ? ' active' : ''}}">
                <a href="{{$action}}">{{$action_name}}</a>
            </div>
        @endforeach
    @endif
</div>
