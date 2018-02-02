-- delete existing data
delete from OrderLine;
delete from Orders;
delete from MenuItems;
delete from Customers;
delete from Restaurants;
delete from Drivers;
delete from Supervisors;
delete from Employees;

drop sequence emp_seq;
drop sequence cust_seq;
drop sequence rest_seq;


-- SEQUENCES
Create sequence emp_seq
start with 1
increment by 1
minvalue 0
maxvalue 500
NOCACHE
NOCYCLE;

Create sequence cust_seq
start with 1
increment by 1
minvalue 0
maxvalue 10000
NOCACHE
NOCYCLE;

Create sequence rest_seq
start with 1
increment by 1
minvalue 0
maxvalue 500
NOCACHE
NOCYCLE;



-- TRIGGERS --
CREATE OR REPLACE TRIGGER CHECK_SUPVSR
    BEFORE INSERT ON Supervisors
    FOR EACH ROW
DECLARE
    supvsrct integer :=0;
    sregion varchar(15);

BEGIN
    /*get the region of the supervisor trying to be inserted  (assumes the new supervisor is already an employee) */
    Select empRegion 
    into sregion
    from Employees
    where empId = :NEW.empId; 

    /* find the count of supervisors who has the same region as the inserted supervisor */
    Select count(empId)
    into supvsrct
    FROM Employees natural join Supervisors
    Where empRegion = sregion;

    if supvsrct <> 0 then
        RAISE_APPLICATION_ERROR(-20000, 'Cannot Insert: The given region already has a supervisor.');
    END IF;

END;
/
show errors;


CREATE OR REPLACE TRIGGER fromSameRegion
    BEFORE INSERT ON Orders
    FOR EACH ROW
DECLARE
    l_cRegion Customers.cRegion%type;
    l_empRegion Employees.empRegion%type;
    l_rRegion Restaurants.rRegion%type;
  
BEGIN
    Select cRegion into l_cRegion
    From Customers
    Where cID = :new.cID;

    Select empRegion into l_empRegion
    From Employees
    Where empId = :new.empID;

    Select rRegion into l_rRegion
    From Restaurants
    Where rId = :new.rID; 

    IF (l_cRegion <> l_empRegion) or (l_cRegion <> l_rRegion) or  (l_empRegion <> l_rRegion) THEN
      RAISE_APPLICATION_ERROR(-20001, 'Cannot Insert: Customer, driver, and/or restaurant are not from the same region.');
    END IF; 
END;
/
show errors; 


-- insert values
CREATE OR REPLACE PROCEDURE loadEmployeeData
AS
BEGIN
  insert into Employees values('e'||emp_seq.nextval, 'Olivia Liu', 'San Jose', '4082345679', 8);    -- 1 SUPERVISOR
  insert into Employees values('e'||emp_seq.nextval, 'Adam Jones', 'San Jose', '4082225678', 4); -- 2 DRIVER
  insert into Employees values('e'||emp_seq.nextval, 'Maggie Jones', 'San Jose', '4088458323', 4.5); -- 3 DRIVER
  insert into Employees values('e'||emp_seq.nextval, 'Sherene Bennett', 'San Jose', '4086341212', 5);  -- 4 DRIVER
  
  insert into Employees values('e'||emp_seq.nextval, 'Carla Jepsen', 'San Francisco', '4158948003', 8); -- 5 SUPERVISOR
  insert into Employees values('e'||emp_seq.nextval, 'David Kim', 'San Francisco', '4087896478', 6.5); -- 6 DRIVER
  
  insert into Employees values('e'||emp_seq.nextval, 'Luis Garcia', 'Fremont' , '5104328006', 8); -- 7 SUPERVISOR
  insert into Employees values('e'||emp_seq.nextval, 'Jess Applegate', 'Fremont' , '5104328006', 5); -- 8 DRIVER
  
  insert into Employees values('e'||emp_seq.nextval, 'Doug White', 'Santa Clara', '4083345432', 8); -- 9 SUPERVISOR 
  insert into Employees values('e'||emp_seq.nextval, 'Ira Kassen', 'Santa Clara', '4082553399', 4.5); -- 10 DRIVER 
  
  insert into Employees values('e'||emp_seq.nextval, 'Luna Granger', 'Palo Alto', '4085653432', 8); -- 11 SUPERVISOR
  insert into Employees values('e'||emp_seq.nextval, 'Ron Potter', 'Palo Alto', '6152553389', 4.5); -- 12 DRIVER 
  

  insert into Supervisors values ('e1', 85000); -- SJ
  insert into Supervisors values ('e5', 80000); -- SF
  insert into Supervisors values ('e7', 80000); -- Fremont
  insert into Supervisors values ('e9', 80000); -- Santa Clara
  insert into Supervisors values ('e11', 80000); -- Palo Alto
  
  insert into Drivers values ('e2', 'F5628921', 13.50, 'e1');
  insert into Drivers values ('e3', 'F8214211', 13.50, 'e1');
  insert into Drivers values ('e4', 'F7757382', 14.00, 'e1');
  insert into Drivers values ('e6', 'F3226881', 13.00, 'e5');
  insert into Drivers values ('e8', 'F3456881', 13.00, 'e7');
  insert into Drivers values ('e10', 'F3676881', 13.00, 'e9');
  insert into Drivers values ('e12', 'F8921811', 13.00, 'e11');
END;
/
show errors; 

exec loadEmployeeData;



CREATE OR REPLACE PROCEDURE loadCustomerData
AS
BEGIN
  insert into Customers values('c'||cust_seq.nextval, 'Amelia Hart', '4084156662', '385 Century Dr', 'San Jose');  -- 1
  insert into Customers values('c'||cust_seq.nextval, 'Greg Hart', '4155672003', '421 Lakeside Ave', 'San Francisco'); -- 2
  insert into Customers values('c'||cust_seq.nextval, 'James Bond', '7007000007', '300 Discrete Cir', 'Fremont'); -- 3
  insert into Customers values('c'||cust_seq.nextval, 'Mia Graham', '4083752525', '700 Bellomy St', 'Santa Clara'); -- 4
  insert into Customers values('c'||cust_seq.nextval, 'Grace Liu', '4088218538', 'S De Anza Blvd', 'San Jose'); -- 5
  insert into Customers values('c'||cust_seq.nextval, 'Max Espinoza', '5103823355', '2380 State St', 'Fremont'); -- 6
  insert into Customers values('c'||cust_seq.nextval, 'Marianne Espinoza', '5103823355', '2380 State St', 'Fremont'); -- 7
  insert into Customers values('c'||cust_seq.nextval, 'Trent Kim', '4082876543', '1920 Main St', 'Santa Clara'); -- 8
  insert into Customers values('c'||cust_seq.nextval, 'Cath Allens', '4152276343', '250 University Ave', 'Palo Alto'); -- 9
END;
/
show errors; 

exec loadCustomerData;



CREATE OR REPLACE PROCEDURE loadRestaurantData
AS
BEGIN
  insert into Restaurants values ('r'||rest_seq.nextval, 'Via Mia Pizza', 'Italian', '4082444100', '1150 Saratoga Ave 95129', 'San Jose', TO_DATE('02/01/2017', 'mm/dd/yyyy')); -- 1
  insert into Restaurants values ('r'||rest_seq.nextval, 'La Victoria', 'Mexican', '4082985335', '140 E San Carlos St 95112', 'San Jose', TO_DATE('02/01/2017', 'mm/dd/yyyy')); -- 2
  insert into Restaurants values ('r'||rest_seq.nextval, 'La Victoria', 'Mexican', '4089938230', '131 W Santa Clara St 95113', 'San Jose', TO_DATE('03/01/2017', 'mm/dd/yyyy')); -- 3
  insert into Restaurants values ('r'||rest_seq.nextval, 'Athena Grill', 'Italian', '4085679144', '1505 Space Park Dr 95054', 'Santa Clara', TO_DATE('12/01/2016', 'mm/dd/yyyy')); -- 4
  insert into Restaurants values ('r'||rest_seq.nextval, 'Pizza My Heart', 'American', '4082410000', '700 Bellomy St 95050', 'Santa Clara', TO_DATE('11/20/2016', 'mm/dd/yyyy')); -- 5
  insert into Restaurants values ('r'||rest_seq.nextval, 'Eastern Winds', 'Chinese', '5102261588', '6997 Warm Springs Blvd 94539', 'Fremont', TO_DATE('02/01/2017', 'mm/dd/yyyy')); -- 6
  insert into Restaurants values ('r'||rest_seq.nextval, 'Chaat Bhavan', 'Indian', '5107951100', '5355 Mowry Ave 94538', 'Fremont', TO_DATE('01/01/2017', 'mm/dd/yyyy')); -- 7
  insert into Restaurants values ('r'||rest_seq.nextval, 'Osaka Sushi', 'Japanese', '4152558828', '460 Castro St 95114', 'San Francisco', TO_DATE('01/01/2017', 'mm/dd/yyyy')); -- 8
  insert into Restaurants values ('r'||rest_seq.nextval, 'Farmhouse Kitchen', 'Thai', '4158142920', '710 Florida St 94110', 'San Francisco', TO_DATE('12/01/2016', 'mm/dd/yyyy')); -- 9
  insert into Restaurants values ('r'||rest_seq.nextval, 'Oren''s Hummus Shop', 'Israeli', '6507526492', '261 University Ave 94301', 'Palo Alto', TO_DATE('12/01/2016', 'mm/dd/yyyy'));--10
  insert into Restaurants values ('r'||rest_seq.nextval, 'Tamarine Restaurant', 'Vietnamese', '6503258500', '560 University Ave', 'Palo Alto', TO_DATE('10/01/2016', 'mm/dd/yyyy'));
END;
/
show errors; 

exec loadRestaurantData;




-- MenuItems (restaurantId, itemName, itemPrice)
CREATE OR REPLACE PROCEDURE loadMenuItems
AS
BEGIN
  insert into MenuItems values ('r1', 'Italiana Pizza', 7.99); 
  insert into MenuItems values ('r1', 'Stromboli Pizza', 8.99);
  insert into MenuItems values ('r1', 'Siciliana Beef Pizza', 8.99);
  insert into MenuItems values ('r1', 'Traditional Calzone', 6.99);
  
  insert into MenuItems values ('r2', 'Regular Burrito', 5.45);
  insert into MenuItems values ('r2', 'Super Burrito', 6.45);
  insert into MenuItems values ('r2', 'Veggie Burrito', 5.45);
  
  insert into MenuItems values ('r3', 'Regular Burrito', 5.45);
  insert into MenuItems values ('r3', 'Super Burrito', 6.45);
  insert into MenuItems values ('r3', 'Veggie Burrito', 5.45);
  
  insert into MenuItems values ('r5', 'Big Sur - 12 in', 17.00);
  insert into MenuItems values ('r5', 'Davenport - 12 in', 17.00);
  insert into MenuItems values ('r5', 'Maui Wowie - 12 in', 15.00);
  
  insert into MenuItems values ('r4', 'Gyro Plate', 14.50);
  insert into MenuItems values ('r4', 'Chicken Souvlaki Plate', 14.50);
  insert into MenuItems values ('r4', 'Beef Souvlaki Plate', 18.95);
  
  insert into MenuItems values ('r6', 'Orange Chicken', 8.95);
  insert into MenuItems values ('r6', 'Chicken Chow Mein', 7.95);
  
  insert into MenuItems values ('r7', 'Samosa', 3.99);
  insert into MenuItems values ('r7', 'Dabeli', 5.49); 
  insert into MenuItems values ('r7', 'Misal Pav', 6.99); 
  
  insert into MenuItems values ('r8', 'Chicken Teriyaki Plate', 14.95); 
  insert into MenuItems values ('r8', 'Salmon Teriyaki Plate', 16.95); 
  insert into MenuItems values ('r8', 'Agedashi Tofu', 5.95); 
  insert into MenuItems values ('r8', 'Assorted Tempura', 6.95); 
  
  insert into MenuItems values ('r9', 'Fish Cake', 9.00);
  insert into MenuItems values ('r9', 'Tom Yum Kai', 6.00);
  insert into MenuItems values ('r9', 'Pad Thai', 14.00); 
  
  insert into MenuItems values ('r10', 'Hummus Classic', 8.95);
  insert into MenuItems values ('r10', 'Falafel Sandwich', 9.95);
  
  insert into MenuItems values ('r11', 'Banh Mi Roti', 10.00);
  insert into MenuItems values ('r11', 'Shrimp Spring Rolls', 12.00);
  insert into MenuItems values ('r11', 'Wok Beef Noodles', 18.00);

END;
/
show errors;

exec loadMenuItems;

--c1-8, 
CREATE OR REPLACE PROCEDURE loadOrderItems
AS
BEGIN
  insert into Orders values ('c1', 'r1', '51000', TO_DATE('11/21/2017', 'mm/dd/yyyy'), 23.97, 'e2', TO_DATE('11/21/2017', 'mm/dd/yyyy'));
  insert into Orders values ('c5', 'r2', '52000', TO_DATE('11/10/2017', 'mm/dd/yyyy'), 17.35, 'e3', TO_DATE('11/10/2017', 'mm/dd/yyyy'));
  insert into Orders values ('c1', 'r2', '53000', TO_DATE('10/05/2017', 'mm/dd/yyyy'), 29.25, 'e2', TO_DATE('10/05/2017', 'mm/dd/yyyy'));
  insert into Orders values ('c4', 'r4', '54000', TO_DATE('07/19/2017', 'mm/dd/yyyy'), 14.50, 'e10', TO_DATE('07/19/2017', 'mm/dd/yyyy'));
  insert into Orders values ('c8', 'r5', '55000', TO_DATE('05/20/2017', 'mm/dd/yyyy'), 75.00, 'e10', TO_DATE('05/20/2017', 'mm/dd/yyyy'));
  insert into Orders values ('c6', 'r6', '56000', TO_DATE('03/24/2017', 'mm/dd/yyyy'), 15.90, 'e8', TO_DATE('03/24/2017', 'mm/dd/yyyy'));
  insert into Orders values ('c7', 'r7', '57000', TO_DATE('02/17/2017', 'mm/dd/yyyy'), 19.95, 'e8', TO_DATE('02/17/2017', 'mm/dd/yyyy'));
  insert into Orders values ('c2', 'r8', '58000', TO_DATE('01/24/2017', 'mm/dd/yyyy'), 21.90, 'e6', TO_DATE('01/24/2017', 'mm/dd/yyyy'));
  insert into Orders values ('c9', 'r11', '61000', TO_DATE('8/05/2017', 'mm/dd/yyyy'), 108.00, 'e12', TO_DATE('8/05/2017', 'mm/dd/yyyy'));
  insert into Orders values ('c9', 'r11', '59000', TO_DATE('11/05/2017', 'mm/dd/yyyy'), 108.00, 'e12', TO_DATE('11/05/2017', 'mm/dd/yyyy'));
  insert into Orders values ('c9', 'r10', '60000', TO_DATE('11/23/2017', 'mm/dd/yyyy'), 94.00, 'e12', TO_DATE('11/23/2017', 'mm/dd/yyyy'));
  
END;
/
show errors;

exec loadOrderItems;

CREATE OR REPLACE PROCEDURE loadOrderLineItems
AS
BEGIN
  insert into OrderLine values ('51000', 1, 'r1', 'Italiana Pizza', 1, 7.99); 
  insert into OrderLine values ('51000', 2, 'r1', 'Stromboli Pizza', 1, 8.99); 
  insert into OrderLine values ('51000', 3, 'r1', 'Traditional Calzone', 1, 6.99); 
  insert into OrderLine values ('52000', 1, 'r2', 'Super Burrito', 1, 6.45); 
  insert into OrderLine values ('52000', 2, 'r2', 'Regular Burrito', 2, 10.90); 
  insert into OrderLine values ('53000', 1, 'r2', 'Veggie Burrito', 3, 16.35); 
  insert into OrderLine values ('53000', 2, 'r2', 'Super Burrito', 2, 12.90); 
  insert into OrderLine values ('54000', 1, 'r4', 'Gyro Plate', 1, 14.50); 
  insert into OrderLine values ('55000', 1, 'r5', 'Maui Wowie - 12 in', 5, 75.00); 
  insert into OrderLine values ('56000', 1, 'r6', 'Chicken Chow Mein', 2, 15.90); 
  insert into OrderLine values ('57000', 1, 'r7', 'Samosa', 5, 19.95); 
  insert into OrderLine values ('58000', 1, 'r8', 'Chicken Teriyaki Plate', 1, 14.95); 
  insert into OrderLine values ('58000', 2, 'r8', 'Assorted Tempura', 1, 6.95);
  insert into OrderLine values ('59000', 1, 'r11', 'Shrimp Spring Rolls', 9, 12.00);
  insert into OrderLine values ('60000', 1, 'r10', 'Hummus Classic', 5, 8.95);
  insert into OrderLine values ('60000', 2, 'r10', 'Falafel Sandwich', 5, 9.95);
  insert into OrderLine values ('61000', 1, 'r11', 'Shrimp Spring Rolls', 9, 12.00);

END;
/
show errors;

exec loadOrderLineItems;
commit;



