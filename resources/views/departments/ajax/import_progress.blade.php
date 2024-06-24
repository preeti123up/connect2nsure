@include('import.process-form', [
    'headingTitle' => __('app.importExcel') . ' ' . __('Department'),
    'processRoute' => route('departments.import.process'),
    'backRoute' => route('departments.index'),
    'backButtonText' => __('Department'),
])
