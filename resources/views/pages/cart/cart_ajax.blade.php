@extends('layout')
@section('content')

<section id="cart_items" class="py-5">
    <div class="container">
        <div class="breadcrumbs mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{URL::to('/')}}">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Giỏ hàng của bạn</li>
            </ol>
        </div>

        <!-- Thông báo -->
        @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {!! session()->get('message') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @elseif(session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {!! session()->get('error') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="table-responsive">
            <form action="{{url('/update-cart')}}" method="POST">
                @csrf
                <table class="table table-bordered text-center">
                    <thead class="table-primary">
                        <tr>
                            <th>Hình ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng tồn</th>
                            <th>Giá sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(Session::get('cart') == true)
                        @php $total = 0; @endphp
                        @foreach(Session::get('cart') as $key => $cart)
                        @php
                        $subtotal = $cart['product_price'] * $cart['product_qty'];
                        $total += $subtotal;
                        @endphp
                        <tr>
                            <td>
                                <img src="{{asset('public/uploads/product/'.$cart['product_image'])}}" class="img-thumbnail" width="80" alt="{{$cart['product_name']}}">
                            </td>
                            <td>{{$cart['product_name']}}</td>
                            <td>{{$cart['product_quantity']}}</td>
                            <td>{{number_format($cart['product_price'],0,',','.')}}đ</td>
                            <td>
                                <input type="number" min="1" name="cart_qty[{{$cart['session_id']}}]" value="{{$cart['product_qty']}}" class="form-control text-center" style="width: 80px;">
                            </td>
                            <td>{{number_format($subtotal,0,',','.')}}đ</td>
                            <td>
                                <a href="{{url('/del-product/'.$cart['session_id'])}}" class="btn btn-danger btn-sm">
                                    <i class="fa fa-times"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                        <tr class="table-light">
                            <td colspan="4" class="text-start">
                                <button type="submit" class="btn btn-success btn-sm">Cập nhật giỏ hàng</button>
                                <a href="{{url('/del-all-product')}}" class="btn btn-warning btn-sm">Xóa tất cả</a>
                                @if(Session::get('coupon'))
                                <a href="{{url('/unset-coupon')}}" class="btn btn-info btn-sm">Xóa mã giảm giá</a>
                                @endif
                            </td>
                            <td colspan="3" class="text-end">
                                <strong>Tổng tiền: </strong>
                                <span class="text-danger fw-bold">{{number_format($total,0,',','.')}}đ</span>
                            </td>
                        </tr>
                        @if(Session::get('coupon'))
                        <tr>
                            <td colspan="7" class="text-end">
                                @foreach(Session::get('coupon') as $key => $cou)
                                @if($cou['coupon_condition'] == 1)
                                Mã giảm: {{$cou['coupon_number']}}%
                                @php
                                $total_coupon = ($total * $cou['coupon_number']) / 100;
                                $final_total = $total - $total_coupon;
                                @endphp
                                <p>Giảm giá: {{number_format($total_coupon,0,',','.')}}đ</p>
                                <p><strong>Tổng thanh toán: {{number_format($final_total,0,',','.')}}đ</strong></p>
                                @elseif($cou['coupon_condition'] == 2)
                                Mã giảm: {{number_format($cou['coupon_number'],0,',','.')}}đ
                                @php
                                $final_total = $total - $cou['coupon_number'];
                                @endphp
                                <p><strong>Tổng thanh toán: {{number_format($final_total,0,',','.')}}đ</strong></p>
                                @endif
                                @endforeach
                            </td>
                        </tr>
                        @endif
                        @else
                        <tr>
                            <td colspan="7" class="text-center">Giỏ hàng của bạn hiện đang trống!</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </form>
        </div>

        @if(Session::get('cart'))
        <div class="row mt-3">
            <div class="col-md-6">
                <form method="POST" action="{{url('/check-coupon')}}">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="coupon" class="form-control" placeholder="Nhập mã giảm giá">
                        <button type="submit" class="btn btn-primary">Áp dụng</button>
                    </div>
                </form>
            </div>
            <div class="col-md-6 text-end">
                @if(Session::get('customer_id'))
                <a href="{{url('/checkout')}}" class="btn btn-success">Đặt hàng</a>
                @else
                <a href="{{url('/dang-nhap')}}" class="btn btn-secondary">Đăng nhập để đặt hàng</a>
                @endif
            </div>
        </div>
        @endif
    </div>
</section>

@endsection
