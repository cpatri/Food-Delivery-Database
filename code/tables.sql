-- drop tables
drop table orderline;
drop table orders; 
drop table menuitems;
drop table restaurants;
drop table customers;
drop table drivers;
drop table supervisors;
drop table employees;



Create table Employees (
  empId           char(5),
  empName         varchar(20),
  empRegion       varchar(15),
  empPhone        char(13),
  empHours_worked number(3,2), check (empHours_worked <= 8),
  primary key (empId)
);

Create table Supervisors (
  empId,
  salary number(9, 2),
  primary key (empId),
  foreign key (empId) references Employees (empId)
); 

Create table Drivers (
  empId, 
  licenseNo     char(8), 
  wage          number(4, 2),
  supervisorId, 
  primary key   (empId),
  foreign key   (empId) references Employees (empId),
  foreign key   (supervisorId) references Supervisors (empId)
); 

Create table Customers (
  cId      char(5),
  cName    varchar(20),
  cPhone   char(10), 
  cAddress varchar(25), 
  cRegion  varchar(15),
  primary key (cId)
); 

Create table Restaurants (
  rId       char(5),
  rName     varchar(20), 
  rCuisine  varchar(15),
  rPhone    char(13),
  rAddress  varchar(30),
  rRegion   varchar(15),
  rContract_date Date,
  primary key (rId)
);

Create table MenuItems (
  rId,
  itemName    varchar(25),
  itemPrice   number(5,2), 
  primary key (rId, itemName), 
  foreign key (rId) references Restaurants (rId) 
); 

-- Combined with the 'makes' relationship 
Create table Orders (
  cId,
  rId,  
  orderId        char(5), 
  time_made      Date, 
  total          number(5,2),
  empId,
  time_delivered Date,
  primary key   (orderId),
  foreign key   (cId) references Customers (cId),
  foreign key   (rId) references Restaurants (rId),
  foreign key   (empId) references Drivers (empId)
);

-- Combined with the 'listed in' relationship 
Create table OrderLine (
  orderId,
  orderLineId  integer,
  rId,
  itemName, 
  qty          integer,
  subtotal     number(5,2),
  primary key (orderId, orderLineId),  
  foreign key (orderId) references Orders (orderId),
  foreign key (rId, itemName) references MenuItems (rId, itemName)
);
