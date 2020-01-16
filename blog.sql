--全局token
--$token: 生成的token
--$time: 有效时间
global:token:$token => $time

--用户全局表（存储用户id）
global:user:id

--用户详情表
--表名:id:字段
user:$id:id
user:$id:username
user:$id:password
user:$id:salt
user:$id:date_entered

--用户id列表
user:list:id => []

--用户名称表
--$username: 用户名
--$id:  用户id
user:username:$username => $id

----------------------------------------

--动态全局表
global:post:id

--动态数据表
post:id:$id  => user_id
				username
				content
				date_entered


--我的帖子id
post:id => []

----------------------------------------

--我关注的表
--$myId: 我的id  
--$user_id: 被关注id   
flow:userid:$myId =>  $user_id

--关注我的表
flowing:userid:$myId =>  $user_id