@extends('master')
@section('title')
   User Account
@endsection
@section('content')
<div class="row">
    <div class="col-md-12 _padding">
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
             @endif
            @if(session()->has('error'))
                <div class="alert alert-danger">
                    {{ session()->get('error') }}
                </div>
             @endif
        <h4>Chuyển tiền</h4>
        <form action="{{ route('chuyen-tien') }}" method="post">
            @csrf
            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                <div class="form-group">
                    <label for="formGroupExampleInput">Loại chuyển ( Lựa chọn phương thức chuyển tiền )</label>
                    <select name="chuyen_tien" id="chuyen_tien" class="form-control" onclick="showChuyenTien(this)" required>
                        <option value="khac_tai_khoan">Chuyển cho người dùng khác</option>
                        <option value="cung_tai_khoan">Tài khoản chính ---> Tài khoản phụ</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="formGroupExampleInput">SĐT Người Nhận</label>
                    <input type="number" class="form-control" name="user_nhan_tien" id="user_nhan_tien"   />
                </div>
                <div class="form-group">
                    <label for="formGroupExampleInput">Số Tiền</label>
                    <input type="number" class="form-control" name="money_chuyentien" id="money_ct"  required />
                    <p id="tien"></p>
                </div>
                <div class="form-group">
                    <label for="formGroupExampleInput">Mật Khẩu Cấp 2</label>
                    <input type="password" class="form-control" name="password2" id="password2"  required />
                </div>
                <button type="submit" class="btn-sm btn btn-primary">Chuyển Tiền</button>
    </form>
    </div>
</div>
<div class="row">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Loại Chuyển</th>
                <th>Số tiền</th>
                <th>Số tiền</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td scope="row"></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td scope="row"></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>
<script>
    function showChuyenTien(selected){
        var type = selected.value;
        var khac_tk = "khac_tai_khoan";
        var cung_tk = "cung_tai_khoan";

        console.log(type)
        if(type == khac_tk) {
            $( "#user_nhan_tien" ).prop( "disabled", false );
        } else if(type == cung_tk) {
            $( "#user_nhan_tien" ).prop( "disabled", true );
        }
    }
    function commaSeparateNumber(val){
        while (/(\d+)(\d{3})/.test(val.toString())){
          val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
        }
        return val;
      }
    $("#money_ct").focusout(function(){
      
     $('#tien').html("").html(commaSeparateNumber($(this).val()) + "đ")
    });
</script>
@endsection