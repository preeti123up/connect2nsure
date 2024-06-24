@include('import.process-form', [
    'headingTitle' => __('app.importExcel') . ' ' . __('Designation'),
    'processRoute' => route('designations.import.process'),
    'backRoute' => route('designations.index'),
    'backButtonText' => __('Designation'),
])
