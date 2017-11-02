<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Book;  //モデルを読み込んでいる
use Illuminate\Http\Request; 

/**
* 本のダッシュボード表示 */
Route::get('/', function () {
        $books = Book::orderBy('created_at', 'asc')->get();
        return view('books',["books"=>$books]);
    
});

/**
* 新「本」を追加 */
//postで受け取って requestで渡す。$requestに配列で受け取る。
Route::post('/books', function (Request $request) {
    //
      //バリデーション
    $validator = Validator::make($request->all(), [
            'item_name' => 'required|min:3|max:255',
    ]);
     $validator = Validator::make($request->all(), [
            'item_number' => 'required|min:1|max:3',
    ]);
      $validator = Validator::make($request->all(), [
            'item_amount' => 'required|max:6',
    ]);
      $validator = Validator::make($request->all(), [
            'published' => 'required|date',
    ]);
    //バリデーション:エラー
    if ($validator->fails()) {
            return redirect('/')->withInput()->withErrors($validator);
    } 
      // Eloquent モデル
    $books = new Book;
    $books->item_name = $request->item_name;
    $books->item_number = $request->item_number;
    $books->item_amount = $request->item_amount;
    $books->published =  $request->published;
    $books->save();   //「/」ルートにリダイレクト 
    return redirect('/');
});

/**
* 本を削除 */
//{book}は受け皿。
Route::post('/book/delete/{book}', function (Book $book) {
    $book->delete();
    return redirect('/');
    //
});

Route::post('/books/update', function (Request $request) {
  $validator = Validator::make($request->all(), [
        'id' => 'required',
        'item_name' => 'required|min:3|max:255',
        'item_number' => 'required|min:1|max:3',
        'item_amount' => 'required|max:6',
        'published' => 'required|date',
        ]);
        // バリデーション：エラー
        if ($validator->fails()) {
        return redirect('/')
        ->withInput()
        ->withErrors($validator);
        }
        // データ更新
        $books = Book::find($request->id);
        $books->item_name = $request->item_name;
        $books->item_number = $request->item_number;
        $books->item_amount = $request->item_amount;
        $books->published =  $request->published;
        $books->save();   //「/」ルートにリダイレクト 
        return redirect('/');
        
        

});


Route::post('/booksedit/{books}', function(Book $books) {
//{books}id 値を取得 => Book $books id 値の１レコード取得
    return view('booksedit',['book'=>$books]);
});


?>