
<table class="table table-bordered table-hover" id="kt_datatable">
                <thead>
                <tr>
                    <th>Record ID</th>
                    <th>Name</th>
                    <th>email</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data as $row)
                <tr>
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->email }}</td>
                    <td> 
                    <button url="{{ route('clients.edit', $row->id) }}" class="edit btn btn-warning">تعديل</button>
                        <form action="{{ route('clients.destroy', $row->id) }}" class="delete-one d-inline-block" method="post" >
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">حذف</button>
                        </form>
                        </td>
                </tr>
                @endforeach
       
                </tbody>
            </table>

