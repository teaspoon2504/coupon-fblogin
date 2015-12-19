selamat datang {{$user->name}}
<img src="{{$user->profile_picture}}">

@if ($user->subscribed == 1)
 Konten super rahasia
@else
	Gak boleh liat
@endif

@if ($user->email == 'eduterrajogja@gmail.com')
	@foreach ($kupons as $kupon)
		{{ $kupon->kode }} <br>
	@endforeach
	<a href="/admin/kupon/buat">buat kupon</a>
@else
	<form action="kupon/subscribe" method="POST">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<input type="text" name="kode">
		<input type="submit">
	</form>
@endif