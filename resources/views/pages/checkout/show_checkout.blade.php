@extends('layout')
@section('content')

<section id="cart_items">
		<div class="container">
			<div class="breadcrumbs">
				<ol class="breadcrumb">
				  <li><a href="{{URL::to('/')}}">Trang chủ</a></li>
				  <li class="active">Thanh toán giỏ hàng</li>
				</ol>
			</div>

			<div class="register-req">
				<p>Đăng ký hoặc đăng nhập để thanh toán giỏ hàng và xem lại lịch sử mua hàng</p>
			</div><!--/register-req-->

			<div class="shopper-informations">
				<div class="row">
				@if(Session::get('cart') == true)	
					
				<div class="container my-5">
						<div class="row">
							<div class="col-lg-12">
								<!-- Thông báo -->
								@if(session()->has('message'))
									<div class="alert alert-success">
										{!! session()->get('message') !!}
									</div>
								@elseif(session()->has('error'))
									<div class="alert alert-danger">
										{!! session()->get('error') !!}
									</div>
								@endif

								<!-- Bảng giỏ hàng -->
								<div class="card shadow-sm">
									<div class="card-header bg-primary text-white">
										<h4 class="text-center mb-0">Giỏ hàng của bạn</h4>
									</div>
									<div class="card-body">
										<form action="{{url('/update-cart')}}" method="POST">
											@csrf
											<div class="table-responsive">
												<table class="table table-bordered text-center">
													<thead class="table-dark">
														<tr>
															<th>Hình ảnh</th>
															<th>Tên sản phẩm</th>
															<th>Giá</th>
															<th>Số lượng</th>
															<th>Thành tiền</th>
															<th>Hành động</th>
														</tr>
													</thead>
													<tbody>
														@if(Session::get('cart')==true)
															@php $total = 0; @endphp
															@foreach(Session::get('cart') as $key => $cart)
																@php
																	$subtotal = $cart['product_price'] * $cart['product_qty'];
																	$total += $subtotal;
																@endphp
																<tr>
																	<td>
																		<img src="{{asset('public/uploads/product/'.$cart['product_image'])}}" class="img-thumbnail" width="70" alt="{{$cart['product_name']}}">
																	</td>
																	<td>{{$cart['product_name']}}</td>
																	<td>{{number_format($cart['product_price'],0,',','.')}}đ</td>
																	<td>
																		<input type="number" min="1" class="form-control text-center" name="cart_qty[{{$cart['session_id']}}]" value="{{$cart['product_qty']}}">
																	</td>
																	<td>{{number_format($subtotal,0,',','.')}}đ</td>
																	<td>
																		<a href="{{url('/del-product/'.$cart['session_id'])}}" class="btn btn-danger btn-sm">
																			<i class="fa fa-trash"></i> Xóa
																		</a>
																	</td>
																</tr>
															@endforeach
															<tr>
																<td colspan="4" class="text-end fw-bold">Tổng tiền:</td>
																<td colspan="2" class="text-danger fw-bold">{{number_format($total,0,',','.')}}đ</td>
															</tr>
														@else
															<tr>
																<td colspan="6">
																	<div class="alert alert-warning text-center">Làm ơn thêm sản phẩm vào giỏ hàng</div>
																</td>
															</tr>
														@endif
													</tbody>
												</table>
											</div>

											<!-- Các hành động -->
											<div class="d-flex justify-content-between mt-3">
												<button type="submit" class="btn btn-warning">Cập nhật giỏ hàng</button>
												<a href="{{url('/del-all-product')}}" class="btn btn-danger">Xóa tất cả</a>
											</div>
										</form>
									</div>

									<!-- Phần tính phí và mã giảm giá -->
									@if(Session::get('cart'))
										<div class="card-footer">
											<div class="row">
												<!-- Mã giảm giá -->
												<div class="col-md-6">
													<form action="{{url('/check-coupon')}}" method="POST">
														@csrf
														<div class="input-group">
															<input type="text" class="form-control" name="coupon"  placeholder="Nhập mã giảm giá">
															<button type="submit" class="btn btn-success">Áp dụng</button>
														</div>
													</form>
													@if(Session::get('coupon'))
														<p class="mt-2">
															Mã giảm giá đã áp dụng:
															@foreach(Session::get('coupon') as $key => $cou)
																<strong>{{ $cou['coupon_number'] }}{{ $cou['coupon_condition'] == 1 ? '%' : 'đ' }}</strong>
																<a href="{{url('/unset-coupon')}}" class="btn btn-sm btn-danger">Hủy</a>
															@endforeach
														</p>
													@endif
												</div>
											<!-- Tổng tiền sau khi áp dụng -->
											<div class="mt-4 text-center py-3" style="background-color: #f8f9fa; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
												<h5 class="fw-bold mb-0" style="font-size: 2.2rem; font-weight: 1000;">
													<span style="font-size: 2.2rem;">Tổng cộng:</span>
													<span class="text-danger fw-bold" style="font-size: 2.7rem; font-weight: 1200;">
														@php
															$final_total = $total;
															if(Session::get('coupon')) {
																foreach(Session::get('coupon') as $key => $cou) {
																	$discount = $cou['coupon_condition'] == 1 
																		? ($total * $cou['coupon_number']) / 100 
																		: $cou['coupon_number'];
																	$final_total -= $discount;
																}
															}
															if(Session::get('fee')) {
																$final_total += Session::get('fee');
															}
															$formatted_total = number_format($final_total, 0, ',', '.');
														@endphp
														{{ $formatted_total }}đ
													</span>
												</h5>
											</div>


										</div>
									@endif
								</div>
							</div>
						</div>
					</div>

					<div class="container mt-4">
						<div class="row">
							<div class="col-md-8 mx-auto">
								<div class="card shadow-lg">
									<div class="card-header bg-primary text-white text-center">
										<h4>Điền thông tin gửi hàng</h4>
									</div>
									<div class="card-body">
										<!-- Form tính phí vận chuyển -->
										<hr class="my-4">
										<h5 class="text-center">Tính phí vận chuyển</h5>
										<form>
											@csrf
											<div class="mb-3">
												<label for="city" class="form-label">Chọn thành phố</label>
												<select name="city" id="city" class="form-select choose city">
													<option value="">--Chọn tỉnh thành phố--</option>
													@foreach($city as $key => $ci)
														<option value="{{ $ci->matp }}">{{ $ci->name_city }}</option>
													@endforeach
												</select>
											</div>
											<div class="mb-3">
												<label for="province" class="form-label">Chọn quận huyện</label>
												<select name="province" id="province" class="form-select province choose">
													<option value="">--Chọn quận huyện--</option>
												</select>
											</div>
											<div class="mb-3">
												<label for="wards" class="form-label">Chọn xã phường</label>
												<select name="wards" id="wards" class="form-select wards">
													<option value="">--Chọn xã phường--</option>
												</select>
											</div>
											
											<div class="text-center">
												<button type="button" name="calculate_order" class="btn btn-primary btn-lg calculate_delivery">
													Tính phí vận chuyển
												</button>
											</div>
										</form>
										<!-- Form chính -->
										<form method="POST">
											@csrf
											<div class="mb-3">
												<label for="shipping_email" class="form-label">Email</label>
												<input type="email" id="shipping_email" name="shipping_email" class="form-control shipping_email" placeholder="Điền email" required>
											</div>
											<div class="mb-3">
												<label for="shipping_name" class="form-label">Họ và tên người gửi</label>
												<input type="text" id="shipping_name" name="shipping_name" class="form-control shipping_name" placeholder="Họ và tên người gửi" required>
											</div>
											<div class="mb-3">
												<label for="shipping_address" class="form-label">Địa chỉ gửi hàng</label>
												<input type="text" id="shipping_address" name="shipping_address" class="form-control shipping_address" placeholder="Địa chỉ gửi hàng" required>
											</div>
											<div class="mb-3">
												<label for="shipping_phone" class="form-label">Số điện thoại</label>
												<input type="text" id="shipping_phone" name="shipping_phone" class="form-control shipping_phone" placeholder="Số điện thoại" required>
											</div>
											<div class="mb-3">
												<label for="shipping_notes" class="form-label">Ghi chú đơn hàng</label>
												<textarea id="shipping_notes" name="shipping_notes" class="form-control shipping_notes" placeholder="Ghi chú đơn hàng của bạn" rows="3"></textarea>
											</div>
											
											<!-- Hiển thị phí vận chuyển & mã giảm giá -->
											@if(Session::get('fee'))
												<input type="hidden" name="order_fee" class="order_fee" value="{{ Session::get('fee') }}">
											@else
												<input type="hidden" name="order_fee" class="order_fee" value="10000">
											@endif

											@if(Session::get('coupon'))
												@foreach(Session::get('coupon') as $key => $cou)
													<input type="hidden" name="order_coupon" class="order_coupon" value="{{ $cou['coupon_code'] }}">
												@endforeach
											@else
												<input type="hidden" name="order_coupon" class="order_coupon" value="no">
											@endif

											<!-- Chọn hình thức thanh toán -->
											<div class="mb-3">
												<label for="payment_select" class="form-label">Chọn hình thức thanh toán</label>
												<select name="payment_select" id="payment_select" class="form-select payment_select">
													<option value="0">Qua chuyển khoản</option>
													<option value="1">Tiền mặt</option>
												</select>
											</div>
											<div class="col-md-12">
												@php
												$vnd_to_usd = $final_total/25405;
												@endphp
												<div id="paypal-button"></div>
												<input type="hidden" id="vnd_to_usd" value="{{round($vnd_to_usd,2)}}">
											</div>
											
											<!-- Nút xác nhận -->
											<div class="text-center">
												<button type="button" name="send_order" class="btn btn-success btn-lg send_order">
													Xác nhận đơn hàng
												</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>

									
				</div>
				@else
					<!-- Nếu giỏ hàng rỗng -->
					<div class="alert alert-warning text-center">Giỏ hàng của bạn hiện đang rỗng. Vui lòng thêm sản phẩm để tiếp tục thanh toán.</div>
				@endif
			</div>
		</div>
	
	</section> <!--/#cart_items-->

@endsection