<?php
namespace app\index\validate;
use think\Validate;

class Reply extends Validate
{
    protected $rule = [
      'reply_email'  => 'email|require|max:25',
      'reply_content'  => 'require|max:100',
    ];
  protected $message=[
    'reply_email.require'  => '邮箱不能为空',
    'reply_email.email'  => '邮箱格式不正确',
    'reply_email.max'  => '邮箱长度不能超过25个字符',
    'reply_content.require'  => '内容不能为空',
    'reply_content.max'  => '内容长度不能超过100个字符',
  ];
}
?>