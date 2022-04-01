<div class="prof-tabs">
    <a href="{{url($lang,'cabinet')}}" @if(Request::segment(2) == 'cabinet') class="active" @endif>{{ShowLabelById(160,$lang_id)}}</a>
    <a href="{{url($lang,'cart')}}">{{ShowLabelById(3,$lang_id)}}</a>
    <a href="{{url($lang,'orders')}}" @if(Request::segment(2) == 'orders') class="active" @endif>{{ShowLabelById(7,$lang_id)}}</a>
    <a href="{{url($lang,'wish-list')}}" @if(Request::segment(2)  == 'wish-list') class="active" @endif>{{ShowLabelById(24,$lang_id)}}</a>
</div>