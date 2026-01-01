<?php

return [

    /*
     * If set to false, no activities will be saved to the database.
     */
    'enabled' => env('ACTIVITY_LOGGER_ENABLED', true),

    /*
     * When the clean-up command is run, all recorded activities older than this number of days will be deleted.
     */
    'delete_records_older_than_days' => 365,

    /*
     * If no log name is passed to the activity() helper, we use this default log name.
     */
    'default_log_name' => 'default',

    /*
     * You can specify an auth driver here that gets used for determining who is logged in.
     */
    'default_auth_driver' => null,

    /*
     * If set to true, the subject returns soft deleted models.
     */
    'subject_returns_soft_deleted_models' => false,

    /*
     * This model will be used to log activity.
     * It should be alongside the Spatie\Activitylog\Models\Activity interface
     * and extend Illuminate\Database\Eloquent\Model.
     */
    'activity_model' => \Spatie\Activitylog\Models\Activity::class,

    /*
     * This is the name of the table that will be created by the migration and
     * used by the Activity model shipped with this package.
     */
    'table_name' => 'activity_log',

    /*
     * This is the name of the database connection that will be used by the migration and
     * used by the Activity model shipped with this package.
     */
    'database_connection' => env('ACTIVITY_LOGGER_DB_CONNECTION'),
];
