
<?PHP

    function getProcedures()
    {
        $procedures = [];
        // Destination procedures
        $procedures['addDestination'] =
            <<<SQL
                CREATE PROCEDURE addDestination(
                    IN _destination_name varchar(50),
                    IN _destination_desc varchar(5000),
                    IN _image_url varchar(50),
                    IN _website varchar(300),
                    IN _zip varchar(35),
                    IN _line_1 varchar(95),
                    IN _line_2 varchar(95),
                    IN _city varchar(35)
                )
                BEGIN
                    INSERT INTO destination(destination_name, destination_desc, image_url, website, zip, line_1, line_2, city)
                    VALUES (_destination_name, _destination_desc, _image_url, _website, _zip, _line_1, _line_2, _city);
                END
            SQL;
        $procedures['updateDestination'] =
            <<<SQL
                CREATE PROCEDURE updateDestination(
                    IN id int,
                    IN _destination_name varchar(50),
                    IN _destination_desc varchar(5000),
                    IN _image_url varchar(50),
                    IN _website varchar(300),
                    IN _zip varchar(35),
                    IN _line_1 varchar(95),
                    IN _line_2 varchar(95),
                    IN _city varchar(35)
                )
                BEGIN
                    UPDATE destination
                    SET destination_name=_destination_name, destination_desc=_destination_desc, image_url=_image_url, 
                        website=_website, zip=_zip, line_1=_line_1, line_2=_line_2, city=_city
                    WHERE destination_id=id;
                END
            SQL;
        $procedures['deleteDestination'] =
            <<<SQL
            CREATE PROCEDURE deleteDestination(IN id int)
            BEGIN
                DELETE FROM destination WHERE destination_id=id;
            END;
            SQL;
        // Tag procedures
        $procedures ['getTag'] =
            <<<SQL
                CREATE PROCEDURE getTag(IN _tag_id INT)
                BEGIN 
                    SELECT * FROM tag WHERE tag_id = _tag_id;
                END;
            SQL;

        $procedures['addTag'] =
            <<<SQL
                CREATE PROCEDURE addTag(
                    IN _tag_type INT,
                    IN _tag_name VARCHAR(30)
                )                
                BEGIN
                    INSERT INTO tag(tag_type, tag_name)
                    VALUES (_tag_type, _tag_name);
                END
            SQL;
        $procedures['updateTag'] =
            <<<SQL
                CREATE PROCEDURE updateTag(
                    id INT,
                    _tag_type INT,
                    _tag_name VARCHAR(30)
                )
                BEGIN
                    UPDATE tag
                    SET tag_type=_tag_type, tag_name=_tag_name
                    WHERE tag_id=id;
                END
            SQL;
        $procedures['deleteTag'] =
            <<<SQL
            CREATE PROCEDURE deleteTag(IN id int)
            BEGIN
                DELETE FROM tag WHERE tag_id=id;
            END;
            SQL;
        // TagType procedures
        $procedures['addTagType'] =
            <<<SQL
                CREATE PROCEDURE addTagType(IN _tag_type_name VARCHAR(30))                
                BEGIN
                    INSERT INTO tag_type(tag_type_name)
                    VALUES (_tag_type_name);
                END
            SQL;
        $procedures['updateTagType'] =
            <<<SQL
                CREATE PROCEDURE updateTagType(id INT, _tag_type_name VARCHAR(30))
                BEGIN
                    UPDATE tag_type
                    SET tag_type_name=_tag_type_name
                    WHERE tag_type_id=id;
                END
            SQL;
        $procedures['deleteTagType'] =
            <<<SQL
            CREATE PROCEDURE deleteTage(IN id int)
            BEGIN
                DELETE FROM tag WHERE tag_id=id;
            END;
            SQL;
        $procedures['addDestinationTag'] =
            <<<SQL
                CREATE PROCEDURE addDestinationTag(IN _destination_id INT, IN _tag_id INT)                
                BEGIN
                    INSERT INTO destination_tag(destination, tag)
                    VALUES (_destination_id, _tag_id);
                END
            SQL;
        $procedures['deleteDestinationTag'] =
            <<<SQL
                CREATE PROCEDURE deleteDestinationTag(IN _destination_id INT, IN _tag_id INT)
                BEGIN
                    DELETE FROM destination_tag WHERE destination=_destination_id AND tag=_tag_id;
                END;
            SQL;
        $procedures['getDestinationTags'] =
            <<<SQL
                CREATE PROCEDURE getDestinationTags(IN _destination_id INT)
                BEGIN 
                    SELECT tag_id, tag_type, tag_name FROM tag
                        INNER JOIN destination_tag
                            ON destination_tag.tag = tag.tag_id AND destination = _destination_id; 
                END;
            SQL;
        $procedures['getTagDestinations'] =
            <<<SQL
                CREATE PROCEDURE getTagDestinations(IN _tag_id INT)
                BEGIN 
                    SELECT * FROM destination WHERE destination_id IN 
                      (
                         SELECT destination_id FROM destination_tag WHERE tag = _tag_id                      
                       );
                END;
            SQL;
        $procedures['getAllTagsJoinTagTypeName'] =
            <<<SQL
                CREATE PROCEDURE getAllTagsJoinTagType()
                BEGIN 
                    SELECT destination, tag_id, tag_name, tag_type_id, tag_type_name FROM destination_tag
                    JOIN tag ON tag.tag_id = destination_tag.tag
                    JOIN tag_type ON tag_type.tag_type_id = tag.tag_type
                    ORDER BY tag_type.tag_type_name;
                END;
            SQL;


        return $procedures;
    }
?>
