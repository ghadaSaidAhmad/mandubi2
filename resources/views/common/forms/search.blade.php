<form action="{{ $route }}" method="post" class="form-search row">
    @csrf
    <div class="col-md-3">
        <label> {{ $searchLabel }} </label>
    </div>
    <div class="col-md-3">
        <select name="search_type" class="form-control">
            @foreach($types as $value=> $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
    
    
    </div>
    <div class="col-md-3">
        <input type="text" name="search" class="input-search form-control">
    </div>
    <div class="col-md-3">
    @if(isset($categories))
   
    <select name="cat_id" class=" cat form-control">
    <option value="0">-- بحث بالتصنيف --</option>
    @foreach($categories as $c)
    
        <option value="{{ $c->id }}">{{ $c->name }}</option>
    @endforeach
        </select>
   
    @endif
    </div>

</form>
