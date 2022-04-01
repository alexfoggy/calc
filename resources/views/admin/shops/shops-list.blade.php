@extends('admin.app')

@section('content')

	@include('admin.breadcrumbs')

	<div class="list-page">

		@if($groupSubRelations->new == 1)
			@include('admin.list-elements', [
				'actions' => [
					trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
					trans('variables.add_element') => urlForFunctionLanguage($lang, 'createShops/createitem'),
				]
			])
		@else
			@include('admin.list-elements', [
				'actions' => [
					trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
				]
			])
		@endif


		<div class="list-table">
			@if(!empty($shops_list))
				<table class="table" id="tablelistsorter" empty-response="{{trans('variables.list_is_empty')}}">
					<thead>
						<tr>
							<th>{{trans('variables.title_table')}}</th>
							<th>{{trans('variables.edit_table')}}</th>
							@if($groupSubRelations->active == 1)
								<th>{{trans('variables.active_table')}}</th>
							@endif
							<th>{{trans('variables.position_table')}}</th>
							@if($groupSubRelations->del_to_rec == 1)
								<th>{{trans('variables.delete_table')}}</th>
							@endif
						</tr>
					</thead>
					<tbody>
						@foreach($shops_list as $shops)
							<tr id="{{$shops->shops_id}}">
								<td>
									<a>{{ !empty(IfHasName($shops->shops_id, $lang_id, 'shops')) ? IfHasName($shops->shops_id, $lang_id, 'shops') : trans('variables.another_name')}}</a>
								</td>
								<td class="edit-links">
									@foreach($lang_list as $lang_key => $one_lang)
										<a href="{{urlForFunctionLanguage($lang, Str::slug($shops->name).'/edititem/'.$shops->shops_id.'/'.$one_lang->id)}}"{{ !empty(IfHasName($shops->shops_id, $one_lang->id, 'shops')) ? 'class=active' : ''}}>{{$one_lang->lang}}</a>
									@endforeach
								</td>
								@if($groupSubRelations->active == 1)
									<td class="small active-link">
										<a href=""
												class="change-active{{$shops->shopsId->active == 1 ? ' active' : ''}}"
												data-active="{{$shops->shopsId->active}}"
												element-id="{{$shops->shopsId->id}}"></a>
									</td>
								@endif
								<td class="dragHandle" nowrap=""></td>
								@if($groupSubRelations->del_to_rec == 1)
									<td class="check-destroy-element">
										<input type="checkbox" class="remove-all-elements"
												name="destroy_elements[{{$shops->shops_id}}]"
												value="{{$shops->shops_id}}"
												url="{{urlForFunctionLanguage($lang, Str::slug($shops->name).'/destroyShopsFromCart')}}">
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