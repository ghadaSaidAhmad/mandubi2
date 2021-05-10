<div id="edit-one"
     class="modal fade"
     tabindex="-1"
     role="dialog"
     aria-labelledby="تعديل العملاء"
     aria-hidden="true"
     style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">تعديل</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form method="post" action="{{ route('users.update', $object->id) }}" class="form-edit">
                @csrf
                @method('PUT')
                <div class="modal-body">

                        @include('common.forms.input', ['name'=> 'email', 'value'=> $object->email,'type'=> 'email','label'=>  'االبريد'])
                        @include('common.forms.input', ['name'=> 'phone','type'=> 'phone', 'value'=> $object->phone ,'label'=>  'الموبيل'])
                        @include('common.forms.input', ['name'=> 'password','type'=> 'password' , 'label'=> 'كلمة المرور'])
                        @include('common.forms.input', ['name'=> 'cpassword','type'=> 'password' , 'label'=> 'تاكيد كلمة المرور'])


                        
                </div>
                <div class="modal-footer">
                    @include('common.forms.close', ['label'=> 'الغاء'])
                    @include('common.forms.submit', ['label'=> 'حفظ'])
                </div>
            </form>
        </div>
    </div>
</div>

