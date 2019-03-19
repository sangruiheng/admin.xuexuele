<div class="panel-body">
    <div class="list-op" id="list_op" method="post" action="{{url('api/district/search')}}">
        <div class="col-lg-3 pull-right">
            <div class="input-group">
                <input type="text" class="form-control" name="name" placeholder="搜索城市"
                >
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="button"
                                onClick="location.href='{{url('api/district/searchDistrict')}}'">搜索</button>
                </span>
            </div>
        </div>
    </div>
</div>
