# Filament HasManyThrough relation test case

## Installation
- Clone the repository:
- Install all php dependencies with `composer install`
- Create an .env file and setup database credentials
- Run `php artisan migrate`
- Create Filament user with `php artisan make:filament-user`
- Optional: Seed the database with some test data. There's a sql file in `/database/seeders/hasManyThrough.sql`

## Table/Model structure
```
projects
    id - integer
    name - string

environments
    id - integer
    project_id - integer
    name - string
    category - string

deployments
    id - integer
    environment_id - integer
    name - string
    status - string
```

All models have the usual hasMany/belongsTo relations. Projects has a `hasManyThrough` relation to deployments.

## Things that are breaking/not working as expected

### 1) hasManyThrough RelationManager contains data for both relationships
The `DeploymentsRelationManager` table contains fields from both the environment and deployment models:

```php
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('category'), //this should not work, should be environment.category
                Tables\Columns\TextColumn::make('environment.name')
            ])
            ->filters([
                //
            ]);
    }
```

The above code generates a table and:
1) overwrites the name of value for deployments with name value of the environment model.
2) prints the environment category value which should not exists on the top level.

### 2) creating a new deployment fails as environment_id is not populated 
```php
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('status')->required(),
                BelongsToSelect::make('environmentId')
                    ->relationship('environment', 'name')
                    ->required()
            ]);
    }
```

The popup does show the select and populates the right values. Once you choose one and try to save the values you get an error saying that the environment_id is not populated.
```
SQLSTATE[HY000]: General error: 1364 Field 'environment_id' doesn't have a default value (SQL: insert into `deployments` (`name`, `status`, `updated_at`, `created_at`) values (test, test, 2021-12-17 04:13:51, 2021-12-17 04:13:51))
```

## Laravel native method does work
Tinker output:
```php 
>>> $p = App\Models\Project::first()
=> App\Models\Project {#4736
     id: 1,
     name: "Project 1",
     created_at: "2021-12-17 03:26:08",
     updated_at: "2021-12-17 03:26:08",
   }
>>> $p->deployments
=> Illuminate\Database\Eloquent\Collection {#4746
     all: [
       App\Models\Deployment {#4750
         id: 1,
         environment_id: 1,
         name: "v1.0.0-rc1",
         status: "Done",
         created_at: null,
         updated_at: null,
         laravel_through_key: 1,
       },
       App\Models\Deployment {#4747
         id: 2,
         environment_id: 1,
         name: "v1.0.0-rc2",
         status: "Done",
         created_at: null,
         updated_at: null,
         laravel_through_key: 1,
       },
     ],
   }
```
