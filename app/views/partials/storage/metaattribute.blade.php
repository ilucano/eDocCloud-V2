<div class="row small-font">
    <div class="col-sm-8">
    @foreach ($attributeSets as $attribute)
        <?php $user_value = ''; ?>
        <div class="form-group">
        <?php
        switch ($attribute->type) {

            case 'string':

                if (count($attribute->user_value) >= 1) {
                    $user_value = $attribute->user_value[0]['value'];
                }
        ?>
                <label for="{{ $attribute->id }}">{{ $attribute->name }}</label>
                {{ Form::text($attribute->id, $user_value, array('class' => 'form-control')) }}
        <?php
                break;

            case 'select':

                if (count($attribute->user_value) >= 1) {
                    $user_value = $attribute->user_value[0]['value'];
                }

                $dropDown = array('' => '');
       
                foreach ($attribute->attribute_options as $option) {
                    $dropDown[$option->id] = $option->options;
                }

        ?>      
                <label for="{{ $attribute->id }}">{{ $attribute->name }}</label>
                {{ Form::select($attribute->id, $dropDown, $user_value, array('class'=>'form-control')) }}

        <?php
                break;

            case 'checkbox':
                $arrayUserValues = array();
                
                if (count($attribute->user_value) >= 1) {
                    foreach ($attribute->user_value as $item) {
                        $arrayUserValues[] = $item->value;
                    }
                    
                }
                
        ?>      

               
                <label for="{{ $attribute->id }}">{{ $attribute->name }}</label>  
                @foreach ($attribute->attribute_options as $option)
                    <?php 
                        $checked  =  (in_array($option->id, $arrayUserValues)) ? true: false;
                    ?>
                    <div class="checkbox">
                        <label>
                        {{ Form::checkbox($attribute->id.'[]', $option->id, $checked) }} {{ $option->options }}
                        </label>
                    </div>
                @endforeach
                
                {{ Form::hidden($attribute->id.'[]', '') }}
               
        <?php
                break;

            case 'boolean':

                if (count($attribute->user_value) >= 1) {
                    $user_value = $attribute->user_value[0]['value'];
                }
                $dropDown = array('' => '', '1' => 'Yes', '0' => 'No');
        ?>
                 <label for="{{ $attribute->id }}">{{ $attribute->name }}</label>
                {{ Form::select($attribute->id, $dropDown, $user_value, array('class'=>'form-control')) }}

        <?php
                break;

            case 'multiselect':
                $arrayUserValues = array();
                
                if (count($attribute->user_value) >= 1) {
                    foreach ($attribute->user_value as $item) {
                        $arrayUserValues[] = $item->value;
                    }
                    
                }

                $dropDown = array();

                foreach ($attribute->attribute_options as $option) {
                    $dropDown[$option->id] = $option->options;
                }

        ?>
                <label for="{{ $attribute->id }}">{{ $attribute->name }}</label> 
                {{ Form::select($attribute->id.'[]', $dropDown, $arrayUserValues, ['multiple' => 'multiple', 'class' => 'form-control']) }}

                {{ Form::hidden($attribute->id.'[]', '') }}
        <?php 
                break;

            case 'radio':

                if (count($attribute->user_value) >= 1) {
                    $user_value = $attribute->user_value[0]['value'];
                }

        ?>
                <label for="{{ $attribute->id }}">{{ $attribute->name }}</label> 

                 <div class="radio">
                        <label>
                        {{ Form::radio($attribute->id, '', ($user_value == '')) }}  <i>Uncheck</i>
                        </label>
                </div>

                @foreach ($attribute->attribute_options as $option)
                    <?php 
                        $checked  =  ( $option->id == $user_value) ? true: false;
                    ?>
                    <div class="radio">
                        <label>
                        {{ Form::radio($attribute->id, $option->id, $checked) }}  {{ $option->options }}
                      
                        </label>
                    </div>
                @endforeach

               

        <?php
                break;
        }
           
        ?>

        </div>
    @endforeach
    </div>
</div>