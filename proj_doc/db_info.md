FORKLIFT TRACKER DB

users table------------------------------------------------------
id                       bigibt(20)    key      NOT NULL
name                 varchar(255)            NOT NULL
email                 varchar(255)    NOT NULL  
password      varchar(255)    NOT NULL
created_at    timestamp
updated       timestamp
role          Enum('user','admin','driver','checker')
warehouse_id  bigint(20)      ref warehouses.warehouse_id



drivers table-------------------------------------
driver_id    bigibt(20)    key      NOT NULL
user_id      bigibt(20)    ref     users.id
name         varchar(255)    NOT NULL
license_no   varchar(255)    NOT NULL
status       Enum('Active','Suspensed', 'Inactive')  NOT NULL
created_at   timestamp
updated_at   timestamp



forklifts table -----------------------------------
forklift_id    bigibt(20)    key      NOT NULL
model          varchar(255)    NOT NULL
plate_number   varchar(255)    NOT NULL
status         enum('Active', 'Maintenance', 'Inactive')
created_at     timestamp
updated_at     timestamp


transactions table-------------------------------------
transaction_id   bigibt(20)    key      NOT NULL
forklift_id	 bigint(20)
driver_id	 begin(20)
warehouse_from	 bigint(20)             NOT NULL
warehouse_to	 bigint(20)             NOT NULL
driver_rating	 tinyint(4)
status	         enum('Pending', 'Assigned', 'In Progress', 'Arrive')
created_at       timestamp
updated_at       timestamp
driver_comment	 text
claimed_at	 timestamp
started_at	 timestamp
arrived_at	 timestamp
completed_at	 timestamp
tart_task_image_path	varchar(255)
end_task_image_path	varchar(255)



transaction_details table-------------------------------------
detail_id        bigint(20)    key      NOT NULL
transaction_id   bigint(20)    ref      transactions.transaction_id
item_name	 varchar(255)		 
quantity	 int(11)
unit	         varchar(255)
description	 text
created_at       timestamp
updated_at       timestamp



warehouses table---------------------------------------------
warehouse_id    bigint(20)    key      NOT NULL
name	        varchar(255)
location	varchar(255)
created_at      timestamp
updated_at      timestamp
status	        enum('Active', 'Inactive')



warehouse_location table------------------------------------------///
location_id     bigint(20)    key      NOT NULL
warehouse_id    bigint(20)    ref   warehouses.warehouse_id
created_at      timestamp
updated_at      timestamp


route table------------------------------------------------- ///
route_id            bigint(20)    key      NOT NULL
origin_location_id    ref   warehouses.name
dest_location_id      ref   warehouses.name
distance            numeric
created_at          timestamp
updated_at          timestamp
CONSTRAIN up_route_pair unique (origin_location_id, dest_location_id)



trip_log table-------------------------------------------------
trip_id           bigint(20)    key      NOT NULL
forklift_id       ref      forklifts.forklift_id
route_id          ref      route.route_id
distance          numeric
