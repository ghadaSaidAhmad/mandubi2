
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
                @foreach($data as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td> 
                    <button url="{{ route('users.edit', $user->id) }}" class="edit btn btn-warning">تعديل</button>
                        <form action="{{ route('users.destroy', $user->id) }}" class="delete-one d-inline-block" method="post" >
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">حذف</button>
                        </form>
                        </td>
                </tr>
                @endforeach
       
                </tbody>
            </table>

