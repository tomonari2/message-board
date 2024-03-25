<?php

use Illuminate\Database\Seeder;

use Encore\Admin\Auth\Database\Menu;

class AdminTablesSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Menu::truncate();
    Menu::insert([
      [
        "parent_id" => 0,
        "order" => 1,
        "title" => "管理画面設定",
        "icon" => "fa-tasks",
        "uri" => NULL,
        "permission" => NULL
      ],
      [
        "parent_id" => 1,
        "order" => 1,
        "title" => "投稿一覧",
        "icon" => "fa-bars",
        "uri" => "posts",
        "permission" => NULL
      ],
      [
        "parent_id" => 1,
        "order" => 2,
        "title" => "ユーザー一覧",
        "icon" => "fa-users",
        "uri" => "users",
        "permission" => NULL
      ],
    ]);
  }
}
