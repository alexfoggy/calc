<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Basket;
use App\Models\FeedForm;
use App\Models\GoodsColors;
use App\Models\Orders;
use App\Models\OrdersData;
use App\Models\OrdersUsers;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class OrdersController extends Controller
{

    private $lang;

    public function __construct()
    {
        $this->lang = $this->lang()['lang'];
    }

    public function index()
    {
        $view = 'admin.orders.orders-list';
        $lang = $this->lang;
        $modules_name = $this->menu()['modules_name'];
        $url_for_active_elem = '/'.$lang.'/back/'.$modules_name->modulesId->alias;

        $orders = Orders::orderBy('created_at', 'desc')
                        ->where('deleted', 0)
                        ->paginate(40);

        $new_orders = Orders::where('seen', 0)->count();
        if($new_orders > 0) {
            Orders::where('seen', 0)->update(['seen' => 1]);
        }

        return view($view, get_defined_vars());
    }

    // ajax response for active
    public function changeActive(Request $request)
    {
        $active = $request->input('active');
        $action = $request->input('action');
        $id = $request->input('id');

        $element_id = Orders::findOrFail($id);

        if(!is_null($element_id))
            $element_name = $element_id->ordersUsers->name;
        else
            return response()->json([
                'status' => false,
                'type' => 'error',
                'messages' => [controllerTrans('variables.something_wrong', $this->lang)]
            ]);

        if($active == 1) {
            $change_active = 0;
            $msg = controllerTrans('variables.element_is_inactive', $this->lang, ['name' => $element_name]);
            $text = 'Не оплачено';
        }
        else{
            $change_active = 1;
            $msg = controllerTrans('variables.element_is_active', $this->lang, ['name' => $element_name]);
	        $text = 'Оплачено';
        }

        if($action == 'paid-order')
	        Orders::where('id', $id)->update(['paid' => $change_active]);
        else
            Orders::where('id', $id)->update(['active' => $change_active]);

        return response()->json([
            'status' => true,
            'type' => 'info',
            'text' => $text,
            'messages' => [$msg]
        ]);

    }

    public function editItem($id)
    {
        $view = 'admin.orders.edit-order';
        $modules_name = $this->menu()['modules_name'];
        $url_for_active_elem = '/'.$this->lang.'/back/'.$modules_name->modulesId->alias;

        $orders = Orders::where('id', $id)->first();

        if(is_null($orders)){
            return App::abort(503, 'Unauthorized action.');
        }

        $orderedItems = $orders->basket;
        $orders_info = Orders::join('orders_data','orders.id','=','orders_data.orders_id')
            ->where('orders.id',$id)
            ->get();

        $basket = Basket::whereRaw('basket_id = (SELECT basket_id FROM orders where id='.$id.')')
                        ->get();
//        dd(($basket));
//        foreach ($basket as $basket1) {
//            $size = GoodsSize::where('id',$basket1->goods_size_id)
//                ->get();
//            $colors = GoodsColors::where('id',$basket1->goods_colors_id)
//                ->get();
//        }
        return view($view, get_defined_vars());
    }

    public function editAdminComment(Request $request){

    	$id = $request->input('id');
    	$comment = $request->input('comment');

	    if(empty($id))
		    return response()->json([
			    'status' => false,
			    'messages' => 'Something was wrong'
		    ]);

	    Orders::where('id',$id)->update(['admin_comment' => !empty($comment)? $comment : '']);

	    $updated_order = Orders::where('id',$id)->first();

	    return response()->json([
		    'status' => true,
		    'comment' => $updated_order->admin_comment,
		    'messages' => 'Comment was edited'
	    ]);

    }


    public function ordersCart()
    {
        $view = 'admin.orders.orders-cart';

        $orders = Orders::where('active', 0)
            ->where('deleted', 1)
            ->orderBy('updated_at', 'desc')
            ->get();

        return view($view, get_defined_vars());
    }

    public function destroyOrderFromCart(Request $request)
    {

        $deleted_elements_id = substr($request->input('id'), 1, -1);

        if (!empty($deleted_elements_id)) {
            $deleted_elements_id_arr = explode(',', $deleted_elements_id);

            $orders_id = Orders::whereIn('id', $deleted_elements_id_arr)->get();

            if (!$orders_id->isEmpty()) {

                $del_message = '';

                foreach ($orders_id as $one_orders_id) {

                    if ($one_orders_id->deleted == 1 && $one_orders_id->active == 0) {

                        $del_message .= $one_orders_id->ordersUsers->name . ', ';

                        Orders::destroy($one_orders_id->id);

                    }

                }

                if (!empty($del_message)) {
                    $del_message = substr($del_message, 0, -2) . '<br />' . controllerTrans('variables.success_deleted', $this->lang);
                }

                return response()->json([
                    'status' => true,
                    'del_messages' => $del_message,
                    'deleted_elements' => $deleted_elements_id_arr
                ]);
            }

            return response()->json([
                'status' => false
            ]);
        } else {
            return response()->json([
                'status' => false
            ]);
        }

    }

    public function destroyOrderToCart(Request $request)
    {

        $deleted_elements_id = substr($request->input('id'), 1, -1);

        if (!empty($deleted_elements_id)) {
            $deleted_elements_id_arr = explode(',', $deleted_elements_id);

            $orders_id = Orders::whereIn('id', $deleted_elements_id_arr)->get();



            if (!$orders_id->isEmpty()) {

                $cart_message = '';

                foreach ($orders_id as $one_orders_id) {


                    if ($one_orders_id->deleted == 0) {

                    	if(!empty($one_orders_id->ordersUsers->name))
                            $cart_message .= $one_orders_id->ordersUsers->name . ', ';


                        Orders::where('id', $one_orders_id->id)
                              ->update(['active' => 0, 'deleted' => 1]);
                    }
                }

                if (!empty($cart_message)) {
                    $cart_message = substr($cart_message, 0, -2) . '<br />' . controllerTrans('variables.success_added_cart', $this->lang);
                }

                return response()->json([
                    'status' => true,
                    'cart_messages' => $cart_message,
                    'deleted_elements' => $deleted_elements_id_arr
                ]);
            }

            return response()->json([
                'status' => false
            ]);
        } else {
            return response()->json([
                'status' => false
            ]);
        }
    }

    public function restoreOrder(Request $request)
    {

        $restored_elements_id = substr($request->input('id'), 1, -1);

        if (!empty($restored_elements_id)) {
            $restored_elements_id_arr = explode(',', $restored_elements_id);

            $promotion_item_elems_id = Orders::whereIn('id', $restored_elements_id_arr)->get();

            if (!$promotion_item_elems_id->isEmpty()) {

                $cart_message = '';

                foreach ($promotion_item_elems_id as $one_promotion_item_elems_id) {

                    $promotion_name = $one_promotion_item_elems_id->ordersUsers->name;

                    if ($one_promotion_item_elems_id->restored == 0) {

                        $cart_message .= $promotion_name . ', ';

                        Orders::where('id', $one_promotion_item_elems_id->id)
                            ->update(['active' => 1, 'deleted' => 0]);
                    }
                }

                if (!empty($cart_message)) {
                    $cart_message = substr($cart_message, 0, -2) . '<br />' . controllerTrans('variables.success_restored', $this->lang);
                }

                return response()->json([
                    'status' => true,
                    'cart_messages' => $cart_message,
                    'restored_elements' => $restored_elements_id_arr
                ]);
            }

            return response()->json([
                'status' => false
            ]);
        } else {
            return response()->json([
                'status' => false
            ]);
        }
    }

    /**
     * return to another url, if method membersList does not exist
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function membersList()
    {
        return redirect(urlForFunctionLanguage($this->lang, ''));
    }

}