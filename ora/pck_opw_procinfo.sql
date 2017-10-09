create or replace package pck_opw_procinfo is

  procedure arg_names(avi_pack_n in varchar2,
                      avi_proc_n in varchar2,
                      aro_params out sys_refcursor);

  ---------------------------------------------------------------------
  procedure prc_packages(avi_uname in varchar2, aro_packs out sys_refcursor);

  ---------------------------------------------------------------------
  procedure prc_procs(avi_uname in varchar2,
                      avi_pack  in varchar2,
                      aro_procs out sys_refcursor);

end pck_opw_procinfo;
/
create or replace package body pck_opw_procinfo is

  procedure arg_names(avi_pack_n in varchar2,
                      avi_proc_n in varchar2,
                      aro_params out sys_refcursor) is
  begin
  
    if avi_pack_n is not null then
      -- We're looking for an object in a package
      open aro_params for
        select a.argument_name, a.data_type, a.in_out
          from sys.stripped_user_arguments a
         where a.package_name = upper(avi_pack_n)
           and a.object_name = upper(avi_proc_n)
         order by a.position;
    else
      -- We're looking for an object
      open aro_params for
        select a.argument_name, a.data_type, a.in_out
          from sys.stripped_user_arguments a
         where a.package_name is null -- in case an object in a package have same name
           and a.object_name = upper(avi_proc_n)
         order by a.position;
    end if;
  end arg_names;

  ---------------------------------------------------------------------
  procedure prc_packages(avi_uname in varchar2, aro_packs out sys_refcursor) is
  
  begin
    open aro_packs for
      select object_name
        from sys.all_objects
       where owner = upper(avi_uname)
         and object_type = 'PACKAGE'
       order by object_name;
  end prc_packages;

  ---------------------------------------------------------------------
  procedure prc_procs(avi_uname in varchar2,
                      avi_pack  in varchar2,
                      aro_procs out sys_refcursor) is
  
  begin
  
    if avi_pack is null then
      open aro_procs for
        select object_name
          from sys.all_objects
         where owner = upper(avi_uname)
           and object_type = 'PROCEDURE'
         order by object_name;
    else
      open aro_procs for
        select procedure_name
          from sys.all_procedures
         where owner = upper(avi_uname)
           and object_name = upper(avi_pack)
         order by object_name;
    end if;
  end prc_procs;

end pck_opw_procinfo;
/