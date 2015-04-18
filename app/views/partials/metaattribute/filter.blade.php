<div class="row">
    <div class="col-sm-8">
    @foreach ($attributeFilters as $filter)
        <div class="form-group">
        <?php
            switch ($filter->type) {
                case 'string':
        ?>      
                <label for="{{ $filter->id }}">{{ $filter->name }}</label>
                {{ Form::text($attributeId, '', array('class' => 'form-control'));
        <?php
                    break;

            }


        ?>

        </div>
    @endforeach
    </div>
</div>