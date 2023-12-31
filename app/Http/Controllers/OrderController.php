<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderProducts;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function sendOrder(Request $request)
    {
        $jsonData = $request->all();

        $user = Auth::user();

        // Formatează JSON-ul
        $clientId =  $user->id;
        $address = $jsonData['address'];
        $price = $jsonData['price'];
        $products = $jsonData['products'];

        //adaugarea comenzii in baza de date
        $order = new Order();
        $order->user_id = $clientId;
        $order->address = $address;
        $order->price = $price;
        $order->save();

        $formattedProducts = [];
        foreach ($products as $product) {
            $productId = $product['id'];
            $productData = Product::where('id', $productId)->first();
            $formattedProducts[] = [
                'id' => $productData['id'],
                'name' => $productData['name'],
                'price' => $productData['price'],
                'quantity' => $product['quantity']
            ];

            //adaugarea produsului comandat in baza de date
            $orderedProduct = new OrderProducts();
            $orderedProduct->order_id = $order->id;;
            $orderedProduct->product_id = $productId;
            $orderedProduct->save();

        }

        // Construirea corpului email-ului
        $body = "<h2>CakeShop - comanda a fost plasata cu succes</h2>";
        $body .= "<div style='margin-bottom: 20px;'><strong>Buna </strong> $user->name !</div>";
        $body .= "<div style='margin-bottom: 20px;'><strong>Adresă:</strong> $address</div>";
        $body .= "<div style='margin-bottom: 20px;'><strong>Preț total:</strong> $price lei</div>";
        $body .= "<h3>Produse:</h3>";
        $body .= "<div style='margin-left: 50px;'>";

        foreach ($formattedProducts as $formattedProduct) {
            $body .= "<div style='margin-bottom: 10px;'><strong>Nume:</strong> {$formattedProduct['name']}</div>";
            $body .= "<div style='margin-bottom: 10px;'><strong>Preț:</strong> {$formattedProduct['price']} lei</div>";
            $body .= "<div style='margin-bottom: 10px;'><strong>Cantitate:</strong> {$formattedProduct['quantity']}</div><br>";
        }
        $body .= "</div>";

        $details = [
            'body' => $body
        ];

        $user = User::where('id', $clientId)->first();

        \Mail::to($user['email'])->send(new \App\Mail\MyTestMail($details));

        return response()->json($request->all());
    }

 

    // Produsele corespunzaoare unei anumite comenzi
    public function getProductsByOrder($orderId)
    {
        $orderedProducts = OrderProducts::with('product')->where('order_id', $orderId)->get();
   
        $productData = [];
        foreach ($orderedProducts as $orderedProduct) {
            $productData[] = [
                'id' => $orderedProduct->id,
                'productName' => $orderedProduct->product->name,
                'productPrice' => $orderedProduct->product->price,
                'productImage' => $orderedProduct->product->image,
            ];
        }
   
        return response()->json($productData);
    }

    public function getOrders()
    {
        $user = Auth::user();

        $orders = Order::where('user_id', $user->id)->get();

        return $orders;
    }

}