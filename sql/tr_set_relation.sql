CREATE DEFINER = CURRENT_USER TRIGGER `cloudware`.`set_relation`
AFTER INSERT ON `order`
FOR EACH ROW
BEGIN
	DECLARE client_ID INT DEFAULT NEW.orderClientID;
	DECLARE item_ID INT DEFAULT NEW.orderItemID;
	DECLARE order_date INT DEFAULT NEW.orderDate;
    IF NEW.orderType = 1 THEN
		INSERT INTO client_equip_relation
		(cerClientID, cerEquipID, cerOrderDate)
		VALUES
		(NEW.orderClientID, NEW.orderItemID, NEW.orderDate);
	ELSE 
		INSERT INTO client_service_relation
        (csrClientID, csrServiceID, cerStartDate)
        VALUES
        (NEW.orderClientID, NEW.orderItemID, NEW.orderDate);
	END IF;
END
