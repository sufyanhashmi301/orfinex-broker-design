@extends('backend.layouts.app')
@section('title')
    {{ __('Add New Question') }}
@endsection
@section('content')

    <style>
        .options-container {
            display: flex;
            align-items: flex-start;
        }

        .option-row {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }

        .option-input {
            margin-right: 8px;
        }
    </style>
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-xl-8">
                        <div class="title-content">
                            <h2 class="title">{{ __('Add New Question Form') }}</h2>
                            <a href="{{ route('admin.ib-form.index') }}" class="title-btn">
                                <i icon-name="corner-down-left"></i>
                                {{ __('Back') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xl-8">
                    <div class="site-card">
                        <div class="site-card-body">
                            <form action="{{route('admin.ib.save.form')}}" method="post" class="row">
                                @csrf

                                <div class="col-xl-12">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Name:') }}</label>
                                        <input type="text" name="name" value="{{ old('name') }}" class="box-input"
                                               placeholder="Question Type Name" required/>
                                    </div>
                                </div>

                                <div class="col-xl-3">
                                    <a href="javascript:void(0)" id="generate"
                                       class="site-btn-xs primary-btn mb-3">{{ __('Add Field option') }}</a>
                                </div>
                                <div class="addOptions">

                                </div>

                                <div class="col-xl-12">
                                    <div class="row">
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                                            <div class="site-input-groups">
                                                <label class="box-input-label" for="">{{ __('Status:') }}</label>
                                                <div class="switch-field">
                                                    <input
                                                        type="radio"
                                                        id="active-status"
                                                        name="status"
                                                        checked=""
                                                        value="1"
                                                    />
                                                    <label for="active-status">{{ __('Active') }}</label>
                                                    <input
                                                        type="radio"
                                                        id="deactivate-status"
                                                        name="status"
                                                        value="0"
                                                    />
                                                    <label for="deactivate-status">{{ __('Deactivate') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <button type="submit" class="site-btn primary-btn w-100">
                                        {{ __('Save Changes') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

    <script>
        $(document).ready(function (e) {
            var i = 0;
            "use strict";

            $("#generate").on('click', function () {
                ++i;
                var form = `<div class="mb-4 option-remove-row row">
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="site-input-groups">
                            <input name="fields[${i}][name]" class="box-input" type="text" value="" required placeholder="Field Name">
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="site-input-groups">
                            <select name="fields[${i}][type]" class="form-select form-select-lg mb-3 field-type">
                                <option value="text">Input Text</option>
                                <option value="checkbox">Checkbox</option>
                                <option value="radio">Radio</option>
                                <option value="dropdown">Dropdown</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12 options-container" style="display: none;">
                        <div class="site-input-groups options">
                            <div class="option-row">
                                <button type="button" class="delete-option"><i class="fas fa-times"></i></button>
                                <input name="fields[${i}][options][]" class="box-input" type="text" value="" placeholder="Option 1">
                                <button type="button" class="add-option">Add Option</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="site-input-groups">
                            <select name="fields[${i}][validation]" class="form-select form-select-lg mb-3">
                                <option value="required">Required</option>
                                <option value="nullable">Optional</option>
                            </select>
                        </div>
                    </div>
                </div>`;
                $('.addOptions').append(form);
            });

            $(document).on('click', '.delete-option', function () {
                var optionRow = $(this).closest('.option-row');
                var optionRows = optionRow.parent().find('.option-row');
                if (optionRows.length > 1) {
                    optionRow.remove();
                }
            });

            $(document).on('change', '.field-type', function () {
                var selectedType = $(this).val();
                var optionsContainer = $(this).closest('.option-remove-row').find('.options-container');

                if (selectedType === 'checkbox' || selectedType === 'radio' || selectedType === 'dropdown') {
                    optionsContainer.show();
                } else {
                    optionsContainer.hide();
                }
            });

            $(document).on('click', '.add-option', function () {
                var optionsContainer = $(this).closest('.options');
                var optionRow = optionsContainer.find('.option-row:first').clone();
                optionRow.find('.box-input').val('');
                optionsContainer.append(optionRow);
            });

            $(document).on('focus', '.box-input', function () {
                $(this).closest('.option-remove-row').find('.delete-option').prop('disabled', false);
            });
        });
    </script>
@endsection
