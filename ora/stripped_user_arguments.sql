 CREATE OR REPLACE FORCE VIEW "SYS"."STRIPPED_USER_ARGUMENTS" ("OBJECT_NAME", "PACKAGE_NAME", "OBJECT_ID", "ARGUMENT_NAME", "POSITION", "DATA_TYPE", "IN_OUT") AS 
   /*
    * Must be compiled by SYS
    *
    * Then the owner of the pck_opw_procinfo package must be granted to select from
    * this view. i.e 'grant select on STRIPPED_USER_ARGUMENTS to MEANDMYSELF'
    *
    *   This is basically a stripped down user_argument view
    *   Must have this new view to get type codes rather than names
    *
    * First Version 2003-08-06
    */
  SELECT NVL(a.procedure$, o.name),                                     -- OBJECT_NAME
         DECODE(a.procedure$,NULL,NULL, o.name),                        -- PACKAGE_NAME
         o.obj#,                                                        -- OBJECT_ID,
         a.argument,                                                    -- ARGUMENT_NAME,
         a.position#,                                                   -- POSITION,
         DECODE(a.type#, 2, DECODE(a.scale, -127, 4, 2), a.type#),      -- DATA_TYPE (2 can be Float(4) or Number(2))
         DECODE(in_out, NULL, 'IN', 1, 'OUT', 2, 'IN/OUT', 'Undefined') -- as IN_OUT
    FROM obj$ o,
         argument$ a
   WHERE o.obj# = a.obj#
     AND owner#   = userenv('SCHEMAID') --This is the main reason why must be compiled by sys
;