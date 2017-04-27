--
-- PostgreSQL database dump
--

-- Dumped from database version 9.6.2
-- Dumped by pg_dump version 9.6.2

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

SET search_path = public, pg_catalog;

--
-- Name: account_type; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE account_type AS ENUM (
    'Bank',
    'Cash',
    'Other Current Assets',
    'Fixed Assets',
    'Accounts Payable',
    'Other Current Liabilities',
    'Equity',
    'Income',
    'Cost of Goods Sold',
    'Expense',
    'Other Income',
    'Other Expense'
);


ALTER TYPE account_type OWNER TO btqcm;

--
-- Name: active_or_inactive; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE active_or_inactive AS ENUM (
    'active',
    'inactive'
);


ALTER TYPE active_or_inactive OWNER TO btqcm;

--
-- Name: ais_report_time_period; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE ais_report_time_period AS ENUM (
    '1',
    '3',
    '6',
    '9',
    '12',
    '24'
);


ALTER TYPE ais_report_time_period OWNER TO btqcm;

--
-- Name: cc_gateway; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE cc_gateway AS ENUM (
    'authorize.netA',
    'authorize.netB',
    'authorize.netC',
    'authorize.netD',
    'moneytree',
    'authorize.netS'
);


ALTER TYPE cc_gateway OWNER TO btqcm;

--
-- Name: cc_type; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE cc_type AS ENUM (
    'M',
    'V',
    'A',
    'D'
);


ALTER TYPE cc_type OWNER TO btqcm;

--
-- Name: communication_methods; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE communication_methods AS ENUM (
    'phone',
    '
email',
    'fax',
    'in-person',
    'mail',
    'text'
);


ALTER TYPE communication_methods OWNER TO btqcm;

--
-- Name: contacts_email_list_type; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE contacts_email_list_type AS ENUM (
    'yes',
    'no',
    'never',
    'bounce'
);


ALTER TYPE contacts_email_list_type OWNER TO btqcm;

--
-- Name: designer_pay_to; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE designer_pay_to AS ENUM (
    'designer',
    'representative'
);


ALTER TYPE designer_pay_to OWNER TO btqcm;

--
-- Name: designer_status_featured; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE designer_status_featured AS ENUM (
    'yes',
    'no',
    'inactive'
);


ALTER TYPE designer_status_featured OWNER TO btqcm;

--
-- Name: designer_terms; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE designer_terms AS ENUM (
    'Net30',
    'Net30CC',
    'Net45',
    'Net60',
    '8/10',
    'COD',
    'CreditCard'
);


ALTER TYPE designer_terms OWNER TO btqcm;

--
-- Name: email_text_type; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE email_text_type AS ENUM (
    'html',
    'text'
);


ALTER TYPE email_text_type OWNER TO btqcm;

--
-- Name: gateway_action; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE gateway_action AS ENUM (
    'AUTH_CAPTURE',
    'AUTH_ONLY',
    'PRIOR_AUTH_CAPTURE',
    'CREDIT',
    'CAPTURE_ONLY',
    'VOID'
);


ALTER TYPE gateway_action OWNER TO btqcm;

--
-- Name: gc_status; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE gc_status AS ENUM (
    'valid',
    'pending',
    'void'
);


ALTER TYPE gc_status OWNER TO btqcm;

--
-- Name: gtd_actions_types; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE gtd_actions_types AS ENUM (
    'errand',
    'call',
    'email',
    'meeting',
    'computer'
);


ALTER TYPE gtd_actions_types OWNER TO btqcm;

--
-- Name: gtd_notes_status; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE gtd_notes_status AS ENUM (
    'new',
    'processed'
);


ALTER TYPE gtd_notes_status OWNER TO btqcm;

--
-- Name: gtd_projects_actions_status; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE gtd_projects_actions_status AS ENUM (
    'new',
    'in progress',
    'completed'
);


ALTER TYPE gtd_projects_actions_status OWNER TO btqcm;

--
-- Name: gtd_waiting_for_status; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE gtd_waiting_for_status AS ENUM (
    'new',
    'received'
);


ALTER TYPE gtd_waiting_for_status OWNER TO btqcm;

--
-- Name: inventory_adjustment_action; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE inventory_adjustment_action AS ENUM (
    'add',
    'subtract'
);


ALTER TYPE inventory_adjustment_action OWNER TO btqcm;

--
-- Name: invoice_terms; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE invoice_terms AS ENUM (
    'net 30',
    '8/10'
);


ALTER TYPE invoice_terms OWNER TO btqcm;

--
-- Name: marital_status; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE marital_status AS ENUM (
    'single',
    'married',
    'married but withhold single'
);


ALTER TYPE marital_status OWNER TO btqcm;

--
-- Name: news_event; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE news_event AS ENUM (
    'new web item',
    'web item markdown',
    'web item other',
    'new web designer',
    'web item shipment',
    'testimonial',
    'other news'
);


ALTER TYPE news_event OWNER TO btqcm;

--
-- Name: on_email_list_type; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE on_email_list_type AS ENUM (
    'yes',
    'no',
    'all'
);


ALTER TYPE on_email_list_type OWNER TO btqcm;

--
-- Name: order_item_action; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE order_item_action AS ENUM (
    'purchase',
    'return'
);


ALTER TYPE order_item_action OWNER TO btqcm;

--
-- Name: order_item_status_type; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE order_item_status_type AS ENUM (
    'tbk',
    'bck',
    'drp',
    'tbs',
    'snc',
    'shp',
    'cxc',
    'cxs',
    'lim',
    'ret',
    'npd',
    'sto',
    'spc',
    'lay',
    'hol',
    'wpu'
);


ALTER TYPE order_item_status_type OWNER TO btqcm;

--
-- Name: order_status; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE order_status AS ENUM (
    'open',
    'closed',
    'canceled'
);


ALTER TYPE order_status OWNER TO btqcm;

--
-- Name: order_transaction_status; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE order_transaction_status AS ENUM (
    'pending',
    'closed',
    'expired',
    'void'
);


ALTER TYPE order_transaction_status OWNER TO btqcm;

--
-- Name: order_transaction_type; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE order_transaction_type AS ENUM (
    'payment',
    'refund'
);


ALTER TYPE order_transaction_type OWNER TO btqcm;

--
-- Name: order_type; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE order_type AS ENUM (
    'web',
    'store'
);


ALTER TYPE order_type OWNER TO btqcm;

--
-- Name: pay_type; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE pay_type AS ENUM (
    'salary',
    'hourly'
);


ALTER TYPE pay_type OWNER TO btqcm;

--
-- Name: permissions_type; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE permissions_type AS ENUM (
    'owner',
    'director',
    'manager',
    'admin',
    'user',
    'store',
    'vendor',
    'bookkeeper'
);


ALTER TYPE permissions_type OWNER TO btqcm;

--
-- Name: send_contact_type; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE send_contact_type AS ENUM (
    'test',
    'store',
    'web',
    'all'
);


ALTER TYPE send_contact_type OWNER TO btqcm;

--
-- Name: shipped_from; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE shipped_from AS ENUM (
    'New Haven',
    'Hamden'
);


ALTER TYPE shipped_from OWNER TO btqcm;

--
-- Name: shipped_from_old; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE shipped_from_old AS ENUM (
    '',
    'New Haven',
    'Hamden'
);


ALTER TYPE shipped_from_old OWNER TO btqcm;

--
-- Name: shipping_carriers; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE shipping_carriers AS ENUM (
    'UPS',
    'USPS',
    'FedEx',
    'DHL'
);


ALTER TYPE shipping_carriers OWNER TO btqcm;

--
-- Name: shipping_locations; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE shipping_locations AS ENUM (
    '1090 Chapel New Haven'
);


ALTER TYPE shipping_locations OWNER TO btqcm;

--
-- Name: status_cctr; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE status_cctr AS ENUM (
    'AUTH',
    'CAPT',
    'AUTH_EXP',
    'AUTH_VOID',
    'CRDT'
);


ALTER TYPE status_cctr OWNER TO btqcm;

--
-- Name: trans_type; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE trans_type AS ENUM (
    'deposit',
    'withdrawal'
);


ALTER TYPE trans_type OWNER TO btqcm;

--
-- Name: transaction_method; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE transaction_method AS ENUM (
    '',
    'cash',
    'check',
    'credit card',
    'debit card',
    'gift certificate',
    'store credit'
);


ALTER TYPE transaction_method OWNER TO btqcm;

--
-- Name: vendor_contact_type; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE vendor_contact_type AS ENUM (
    'designer',
    'representative',
    'factor'
);


ALTER TYPE vendor_contact_type OWNER TO btqcm;

--
-- Name: vendor_rep_terms; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE vendor_rep_terms AS ENUM (
    'Net30',
    'Net45',
    'Net60',
    '8/10',
    'COD',
    'CreditCard'
);


ALTER TYPE vendor_rep_terms OWNER TO btqcm;

--
-- Name: yes_no; Type: TYPE; Schema: public; Owner: btqcm
--

CREATE TYPE yes_no AS ENUM (
    'yes',
    'no'
);


ALTER TYPE yes_no OWNER TO btqcm;

--
-- Name: best_customers(order_type, timestamp without time zone, timestamp without time zone); Type: FUNCTION; Schema: public; Owner: btqcm
--

CREATE FUNCTION best_customers(order_type order_type, start_dt timestamp without time zone, end_dt timestamp without time zone) RETURNS void
    LANGUAGE plpgsql
    AS $_$
begin

    -- First get the orders in the given date range, joined to a status row for each item in the order.
    
    create temp table orders_with_items_and_status (
      contact_id          bigint,
      order_id 	          bigint,
      order_type          order_type,
      order_item_id       bigint,
      item_price          numeric,
      status              order_item_status_type,
      is_pending          boolean,
      is_cancelled        boolean,
      is_normal           boolean,
      is_return           boolean,
      is_web_return       boolean,
      is_store_return     boolean
    ) on commit drop;

    --create index on orders_with_items_and_status (contact_id);
    
    insert into orders_with_items_and_status
      select o.contact_id, o.id, o.order_type, oi.id, oi.item_price, ois.order_item_status
      from orders o 
      join order_items oi on o.id = oi.order_id
      join order_item_status ois on ois.order_item_id = oi.id
      where (start_dt is null or o.order_dt >= start_dt)
        and (end_dt is null or o.order_dt <= end_dt)
        and ($1 is null or o.order_type = $1);

/*   create temp table orders_with_items_and_status on commit drop as
      select o.contact_id, o.id order_id, o.order_type, oi.id order_item_id, oi.item_price, ois.order_item_status status,
      null::boolean is_pending, null::boolean is_cancelled, null::boolean is_normal, null::boolean is_return, null::boolean is_web_return, null::boolean is_store_return
      from orders o 
      join order_items oi on o.id = oi.order_id
      join order_item_status ois on ois.order_item_id = oi.id
      where (start_dt is null or o.order_dt >= start_dt)
        and (end_dt is null or o.order_dt <= end_dt)
        and ($1 is null or o.order_type = $1);
  */ 

    -- Now categorize those rows based on the status and order_type, for easier summing and counting in the next steps.
    update orders_with_items_and_status o
       set is_pending = 	         (status in ('tbk', 'spc', 'bck', 'tbs')),
       	   is_cancelled = 		 (status in ('cxs', 'cxc')),
	   is_normal =  		 (status in ('shp', 'sto')),
	   is_return =  		 (status = 'ret'),
	   is_web_return = 		 (status = 'ret' and o.order_type = 'web'),
	   is_store_return =  		 (status = 'ret' and o.order_type = 'store');
	  
    create temp table summed on commit drop as 
          select c.contact_id,
	         count(distinct order_id) as num_orders,
		 -- item counts
		 sum(case when is_normal or is_web_return then 1 else 0 end) as total_items,
		 sum(case when is_return then 1 else 0 end) as returned_items,
		 sum(case when is_cancelled then 1 else 0 end) as cancelled_items,
		 0 as net_items,
		 sum(case when is_pending then 1 else 0 end) as pending_items,
		 -- dollar sums
		 sum(case when is_normal or is_web_return then item_price else 0 end) as total,
		 sum(case when is_return then item_price else 0 end) as returned,
		 sum(case when is_cancelled then item_price else 0 end) as cancelled,
 		 0::numeric(10,2) as net,
		 sum(case when is_pending then item_price else 0 end) as pending,
                 array_agg(distinct order_id) order_ids, array_agg(order_item_id) order_item_ids
	  from orders_with_items_and_status c
	  group by c.contact_id;

    create unique index on summed (contact_id);

    -- Convenience columns ?
    update summed set net_items = total_items - returned_items - cancelled_items,
                      net = total - returned - cancelled;

end;
$_$;


ALTER FUNCTION public.best_customers(order_type order_type, start_dt timestamp without time zone, end_dt timestamp without time zone) OWNER TO btqcm;

--
-- Name: bool_to_enum(boolean); Type: FUNCTION; Schema: public; Owner: btqcm
--

CREATE FUNCTION bool_to_enum(val boolean) RETURNS yes_no
    LANGUAGE plpgsql
    AS $$
begin
  return case
    when val is null then null
    when val then 'yes'
    else 'no'
  end;
end;
$$;


ALTER FUNCTION public.bool_to_enum(val boolean) OWNER TO btqcm;

--
-- Name: convert_bool_column(text, text); Type: FUNCTION; Schema: public; Owner: btqcm
--

CREATE FUNCTION convert_bool_column(table_name text, column_name text) RETURNS void
    LANGUAGE plpgsql
    AS $$
declare
  sql1 text;
  sql2 text;
  sql3 text;
  sql4 text;
  def boolean;
begin
  select into def pad.adsrc
    from pg_class pc
    join pg_attribute pa on pc.oid = pa.attrelid
    join pg_attrdef pad on pc.oid = pad.adrelid and pa.attnum = pad.adnum
    where pc.relname = table_name and pa.attname = column_name;
  raise notice 'The default is %', def;

  sql1 = 'alter table ' || table_name || ' alter column ' || column_name;

  if def is not null then
    sql2 = sql1 || ' drop default';
    execute sql2;
  end if;

  sql3 = sql1 || ' type yes_no using bool_to_enum( ' || column_name || ' )';
  execute sql3;

  if def is not null then    
    sql4 = sql1 || ' set default ''' || (case when def then 'yes' else 'no' end)  || '''';
    execute sql4;
  end if;
end;
$$;


ALTER FUNCTION public.convert_bool_column(table_name text, column_name text) OWNER TO btqcm;

--
-- Name: designer_search_strings(bigint); Type: FUNCTION; Schema: public; Owner: btqcm
--

CREATE FUNCTION designer_search_strings(id bigint) RETURNS SETOF text
    LANGUAGE plpgsql
    AS $_$
begin
  return query
    with words as (
      select regexp_split_to_table(alias, E'\\s+') as word from designer_search_aliases a where a.id = $1
      union
      select regexp_split_to_table(name, E'\\s+') as word from vendor_designers d where d.id = $1
    )
    select distinct(lower(word)) from words;
end;
$_$;


ALTER FUNCTION public.designer_search_strings(id bigint) OWNER TO btqcm;

--
-- Name: designer_search_vector(bigint); Type: FUNCTION; Schema: public; Owner: btqcm
--

CREATE FUNCTION designer_search_vector(id bigint) RETURNS tsvector
    LANGUAGE plpgsql
    AS $_$
declare
  stext text;
begin
  select string_agg(word, ' ') from designer_search_strings($1) word into stext;
  return to_tsvector(stext);
end;
$_$;


ALTER FUNCTION public.designer_search_vector(id bigint) OWNER TO btqcm;

--
-- Name: fill_tracker2_table(bigint, bigint); Type: FUNCTION; Schema: public; Owner: btqcm
--

CREATE FUNCTION fill_tracker2_table(start_id bigint, max_rows bigint) RETURNS void
    LANGUAGE plpgsql
    AS $$
declare
    trow record;
    br_id bigint;
    wp_id bigint;
    ref_id bigint;
    n bigint := 0;
begin
    for trow in select * from tracker where id >= start_id order by id limit max_rows
    loop
       select id into br_id from tracker_browsers where browser = trow.browser;
       if br_id is null then
           insert into tracker_browsers (browser) values (trow.browser) returning id into br_id;
       end if;
       select id into ref_id from tracker_referers where url = trow.referer;
       if ref_id is null then
           insert into tracker_referers (url) values (trow.referer) returning id into ref_id;
       end if;
       select id into wp_id from tracker_web_pages where url_path = trow.web_page;
       if wp_id is null then
           insert into tracker_web_pages (url_path) values (trow.web_page) returning id into wp_id;
       end if;
       insert into tracker2
          (id, visitdatetime, referer, ip, browser, web_page, cookie_id, ad_code)
          values
    (trow.id, trow.visitdatetime, ref_id, trow.ip::inet, br_id, wp_id, trow.cookie_id, trow.ad_code);
       n := n + 1;
    end loop;
    raise notice 'Finished % rows. Last tracker.id = %', n, trow.id;
    return;
end;
$$;


ALTER FUNCTION public.fill_tracker2_table(start_id bigint, max_rows bigint) OWNER TO btqcm;

--
-- Name: item_profit(real, real); Type: FUNCTION; Schema: public; Owner: btqcm
--

CREATE FUNCTION item_profit(price real, cost real) RETURNS real
    LANGUAGE plpgsql
    AS $$
begin
   if cost = 0 then return 0; end if;
   return 100.0 * (price - cost) / cost;
end;
$$;


ALTER FUNCTION public.item_profit(price real, cost real) OWNER TO btqcm;

--
-- Name: on_contacts_email_change(); Type: FUNCTION; Schema: public; Owner: btqcm
--

CREATE FUNCTION on_contacts_email_change() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
begin
  NEW.email := lower(NEW.email);
  return NEW;
end;
$$;


ALTER FUNCTION public.on_contacts_email_change() OWNER TO btqcm;

--
-- Name: on_inventory_change(); Type: FUNCTION; Schema: public; Owner: btqcm
--

CREATE FUNCTION on_inventory_change() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
begin
refresh materialized view inventory_search_view;
return null;
end;
$$;


ALTER FUNCTION public.on_inventory_change() OWNER TO btqcm;

--
-- Name: sales_by_designer_shipments(date, date, date, bigint, bigint); Type: FUNCTION; Schema: public; Owner: btqcm
--

CREATE FUNCTION sales_by_designer_shipments(start_date date, shipments_end_date date, orders_end_date date, season bigint, designer bigint) RETURNS void
    LANGUAGE plpgsql
    AS $$
declare
    c integer;
    season_arg bigint; -- because column names conflict with "season"
begin
    -- No nulls
    if start_date         is null then raise 'start_date cannot be null'; end if;
    if shipments_end_date is null then raise 'shipments_end_date cannot be null'; end if;

    -- Map 0 to null for these arguments
    if season = 0 then season := null; end if;
    if designer = 0 then designer := null; end if;

    season_arg := season;


    -- First we get the shipments, joined to designer to get the designer's name
    create temp table _shipments on commit drop as
      select ish.*, d.name
      from inventory_shipments ish join vendor_designers d on ish.designer_id = d.id
      where ish.receive_date BETWEEN start_date AND shipments_end_date
        and (designer is null or d.id = designer);

    -- Next get all the items (breakdowns) in the shipment. There is a row row here for each item breakdown row (size and color), not just items.
    -- Add a window function over the item so that code can see when where on the first, last, and nth item_breakdown?
    create temp table _items on commit drop as 
      select s.id as shipment_id, s.receive_date, 
             isi.item_id, isi.item_breakdown_id, isi.quantity, isi.unit_cost,
             ii.style_number, ii.name, ii.price, 
             iib.item_color_id, iib.size_id,
	     seas.season,
	     -- "primary category" like in PHP code. Is this right?
	     (select name from inventory_categories c join inventory_item_categories ic on c.id = ic.category_id where ic.item_id = ii.id order by ic.id limit 1) as category,
	     col.color, sizes.size
      from _shipments s
      join inventory_shipment_items isi  on s.id = isi.shipment_id
      join inventory_items ii            on isi.item_id = ii.id
      -- left join because not all items have seasons
      left join inventory_seasons seas   on ii.season_id = seas.id     
      join inventory_item_breakdown iib  on isi.item_breakdown_id = iib.id
      join inventory_item_colors col     on iib.item_color_id = col.id
      join inventory_sizes sizes         on iib.size_id = sizes.id
      -- Should it match items with no season, even when searching for a season?
      where (season_arg is null or ii.season_id is null or season_arg = ii.season_id)
      order by s.id, ii.id;

    -- ???
    create index on _items (item_id);
    create index on _items (item_breakdown_id);
    create index on _items (item_id, item_breakdown_id);        
    
    -- Images, muliple per item
    create temp table _images (like inventory_item_images) on commit drop;

    insert into _images 
       select * from inventory_item_images 
       where item_id in (select item_id from _items) order by item_id, imageorder;
    
    -- todo: indexs?

    -- Orders
    
    create temp table _order_items on commit drop as 
    select oi.*, o.order_dt, o.contact_id, o.order_type, 'normal' as which_table
    from _items i 
    join order_items oi on oi.item_bd_id = i.item_breakdown_id -- and item_id? <- is that redundant?
    join orders o       on o.id = oi.order_id 
    where oi.item_id = i.item_id and o.order_dt >= start_date and (orders_end_date is null or o.order_dt <= orders_end_date)
    order by i.item_id, o.id;

    -- Possible special orders ?
    -- TODO: connect to shipments
    create temp table _sp_order_items on commit drop as
    select oi.*, o.order_dt, o.contact_id, o.order_type, 'special' as which_table
    from _shipments s
    join inventory_shipment_items isi   on s.id = isi.shipment_id
    join order_items oi                 on oi.item_bd_id = isi.item_breakdown_id
    join orders o                       on o.id = oi.order_id
    join order_item_status ois          on ois.order_item_id = oi.id
    where ois.order_item_delivery_date between s.receive_date AND s.receive_date + interval '10 days';

    -- Adjustments?

    create temp table _adjustments on commit drop as 
    select ia.* 
    from _items i
    join inventory_adjustments ia on ia.item_breakdown_id = i.item_breakdown_id
    where ia.action = 'subtract' AND ia.dt >= i.receive_date AND (orders_end_date is null or dt <= orders_end_date);

    create temp table _order_item_status on commit drop as 
    select ois.*
    from order_item_status ois where ois.order_item_id in (
        select id from _order_items union select id from _sp_order_items
    );


    return;
end;
$$;


ALTER FUNCTION public.sales_by_designer_shipments(start_date date, shipments_end_date date, orders_end_date date, season bigint, designer bigint) OWNER TO btqcm;

--
-- Name: search_inventory(text); Type: FUNCTION; Schema: public; Owner: btqcm
--

CREATE FUNCTION search_inventory(query text) RETURNS TABLE(id bigint, designer character varying, rank real)
    LANGUAGE plpgsql
    AS $_$
declare
  tsq tsquery;
  for_like text;
begin
  select plainto_tsquery($1) into tsq;
  for_like := $1 || '%';
  return query
    select ii.id, d.name as designer, ts_rank(isv.ts_vec, tsq) as rank
    from inventory_search_view isv
    join inventory_items ii on ii.id = isv.id
    join vendor_designers d on d.id = ii.designer_id
	  where ii.status_web = 'active' and d.status_web = 'active'
      and (isv.ts_vec @@ tsq
           or ii.style_number ilike for_like
           or d.name ilike for_like)
    -- The old sort
		-- order by ii.designer_id, ii.price_web desc, ii.enter_date desc;
    order by rank desc;
  return;
end;
$_$;


ALTER FUNCTION public.search_inventory(query text) OWNER TO btqcm;

--
-- Name: update_test_modified_column(); Type: FUNCTION; Schema: public; Owner: btqcm
--

CREATE FUNCTION update_test_modified_column() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.modified = now(); 
   RETURN NEW;
END;
$$;


ALTER FUNCTION public.update_test_modified_column() OWNER TO btqcm;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: accounts; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE accounts (
    id bigint NOT NULL,
    account character varying(100) NOT NULL,
    account_type account_type DEFAULT 'Bank'::account_type NOT NULL,
    descrip text DEFAULT ''::text NOT NULL,
    parent bigint
);


ALTER TABLE accounts OWNER TO btqcm;

--
-- Name: accounts_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE accounts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE accounts_id_seq OWNER TO btqcm;

--
-- Name: accounts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE accounts_id_seq OWNED BY accounts.id;


--
-- Name: ad_codes; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE ad_codes (
    id bigint NOT NULL,
    start_dt timestamp without time zone NOT NULL,
    end_dt timestamp without time zone,
    description text NOT NULL,
    results text NOT NULL
);


ALTER TABLE ad_codes OWNER TO btqcm;

--
-- Name: ad_codes_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE ad_codes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE ad_codes_id_seq OWNER TO btqcm;

--
-- Name: ad_codes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE ad_codes_id_seq OWNED BY ad_codes.id;


--
-- Name: admin_roles; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE admin_roles (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE admin_roles OWNER TO btqcm;

--
-- Name: admin_roles_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE admin_roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE admin_roles_id_seq OWNER TO btqcm;

--
-- Name: admin_roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE admin_roles_id_seq OWNED BY admin_roles.id;


--
-- Name: admins; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE admins (
    id bigint NOT NULL,
    name character varying(50),
    username character varying(20) NOT NULL,
    permissions permissions_type DEFAULT 'admin'::permissions_type NOT NULL,
    employee_id bigint,
    password_hash character varying(255)
);


ALTER TABLE admins OWNER TO btqcm;

--
-- Name: admins_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE admins_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE admins_id_seq OWNER TO btqcm;

--
-- Name: admins_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE admins_id_seq OWNED BY admins.id;


--
-- Name: cart_items; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE cart_items (
    id bigint NOT NULL,
    cart_id bigint NOT NULL,
    item_id bigint NOT NULL,
    item_qty integer NOT NULL,
    item_color_id bigint,
    size_id bigint,
    gift_certificate_id bigint,
    enter_dt timestamp without time zone NOT NULL
);


ALTER TABLE cart_items OWNER TO btqcm;

--
-- Name: cart_items_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE cart_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE cart_items_id_seq OWNER TO btqcm;

--
-- Name: cart_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE cart_items_id_seq OWNED BY cart_items.id;


--
-- Name: carts; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE carts (
    id bigint NOT NULL,
    enter_dt timestamp without time zone NOT NULL,
    tracker_cookie_id text NOT NULL,
    contact_id bigint
);


ALTER TABLE carts OWNER TO btqcm;

--
-- Name: carts_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE carts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE carts_id_seq OWNER TO btqcm;

--
-- Name: carts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE carts_id_seq OWNED BY carts.id;


--
-- Name: category_search_aliases; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE category_search_aliases (
    id bigint NOT NULL,
    alias character varying NOT NULL
);


ALTER TABLE category_search_aliases OWNER TO btqcm;

--
-- Name: checking; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE checking (
    id bigint NOT NULL,
    trans_date date NOT NULL,
    trans_type trans_type NOT NULL,
    amount numeric(10,2) DEFAULT 0.00 NOT NULL,
    descrip text DEFAULT ''::text NOT NULL,
    check_num integer DEFAULT 0 NOT NULL,
    check_pay_to text DEFAULT ''::text NOT NULL,
    clear_date date,
    account1 bigint,
    amount1 numeric(10,2) DEFAULT 0.00 NOT NULL,
    account2 bigint,
    amount2 numeric(10,2) DEFAULT 0.00 NOT NULL,
    account3 bigint,
    amount3 numeric(10,2) DEFAULT 0.00 NOT NULL,
    account4 bigint,
    amount4 numeric(10,2) DEFAULT 0.00 NOT NULL,
    account5 bigint,
    amount5 numeric(10,2) DEFAULT 0.00 NOT NULL,
    account6 bigint,
    amount6 numeric(10,2) DEFAULT 0.00 NOT NULL,
    account7 bigint,
    amount7 numeric(10,2) DEFAULT 0.00 NOT NULL,
    account8 bigint,
    amount8 numeric(10,2) DEFAULT 0.00 NOT NULL,
    account9 bigint,
    amount9 numeric(10,2) DEFAULT 0.00 NOT NULL,
    account10 bigint,
    amount10 numeric(10,2) DEFAULT 0.00 NOT NULL
);


ALTER TABLE checking OWNER TO btqcm;

--
-- Name: checking_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE checking_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE checking_id_seq OWNER TO btqcm;

--
-- Name: checking_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE checking_id_seq OWNED BY checking.id;


--
-- Name: checking_bofa; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE checking_bofa (
    id bigint DEFAULT nextval('checking_id_seq'::regclass) NOT NULL,
    trans_date date NOT NULL,
    trans_type trans_type NOT NULL,
    amount numeric(10,2) DEFAULT 0.00 NOT NULL,
    descrip text DEFAULT ''::text NOT NULL,
    check_num integer DEFAULT 0 NOT NULL,
    check_pay_to text DEFAULT ''::text NOT NULL,
    clear_date date,
    account1 bigint,
    amount1 numeric(10,2) DEFAULT 0.00 NOT NULL,
    account2 bigint,
    amount2 numeric(10,2) DEFAULT 0.00 NOT NULL,
    account3 bigint,
    amount3 numeric(10,2) DEFAULT 0.00 NOT NULL,
    account4 bigint,
    amount4 numeric(10,2) DEFAULT 0.00 NOT NULL,
    account5 bigint,
    amount5 numeric(10,2) DEFAULT 0.00 NOT NULL,
    account6 bigint,
    amount6 numeric(10,2) DEFAULT 0.00 NOT NULL,
    account7 bigint,
    amount7 numeric(10,2) DEFAULT 0.00 NOT NULL,
    account8 bigint,
    amount8 numeric(10,2) DEFAULT 0.00 NOT NULL,
    account9 bigint,
    amount9 numeric(10,2) DEFAULT 0.00 NOT NULL,
    account10 bigint,
    amount10 numeric(10,2) DEFAULT 0.00 NOT NULL
);


ALTER TABLE checking_bofa OWNER TO btqcm;

--
-- Name: checkout_gcs; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE checkout_gcs (
    id bigint NOT NULL,
    checkout_id bigint NOT NULL,
    gc_id bigint NOT NULL
);


ALTER TABLE checkout_gcs OWNER TO btqcm;

--
-- Name: checkout_gcs_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE checkout_gcs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE checkout_gcs_id_seq OWNER TO btqcm;

--
-- Name: checkout_gcs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE checkout_gcs_id_seq OWNED BY checkout_gcs.id;


--
-- Name: checkouts; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE checkouts (
    id bigint NOT NULL,
    enter_dt timestamp without time zone NOT NULL,
    cart_id bigint NOT NULL,
    ship_name text NOT NULL,
    ship_method text NOT NULL,
    contact_id bigint NOT NULL,
    promotion_id bigint,
    comments_customer text NOT NULL,
    order_id bigint
);


ALTER TABLE checkouts OWNER TO btqcm;

--
-- Name: checkouts_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE checkouts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE checkouts_id_seq OWNER TO btqcm;

--
-- Name: checkouts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE checkouts_id_seq OWNED BY checkouts.id;


--
-- Name: contact_authnet_payment_profiles; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE contact_authnet_payment_profiles (
    id bigint NOT NULL,
    contact_authnet_profile_id bigint NOT NULL,
    authnet_payment_profile_id bigint NOT NULL,
    created timestamp without time zone NOT NULL
);


ALTER TABLE contact_authnet_payment_profiles OWNER TO btqcm;

--
-- Name: contact_authnet_payment_profiles_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE contact_authnet_payment_profiles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE contact_authnet_payment_profiles_id_seq OWNER TO btqcm;

--
-- Name: contact_authnet_payment_profiles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE contact_authnet_payment_profiles_id_seq OWNED BY contact_authnet_payment_profiles.id;


--
-- Name: contact_authnet_profiles; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE contact_authnet_profiles (
    id bigint NOT NULL,
    contact_id bigint NOT NULL,
    authnet_customer_id bigint NOT NULL,
    created timestamp without time zone NOT NULL
);


ALTER TABLE contact_authnet_profiles OWNER TO btqcm;

--
-- Name: contact_authnet_profiles_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE contact_authnet_profiles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE contact_authnet_profiles_id_seq OWNER TO btqcm;

--
-- Name: contact_authnet_profiles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE contact_authnet_profiles_id_seq OWNED BY contact_authnet_profiles.id;


--
-- Name: contact_requests; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE contact_requests (
    id bigint NOT NULL,
    contact_id bigint NOT NULL,
    search text NOT NULL,
    message text NOT NULL,
    dt timestamp without time zone NOT NULL
);


ALTER TABLE contact_requests OWNER TO btqcm;

--
-- Name: contact_requests_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE contact_requests_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE contact_requests_id_seq OWNER TO btqcm;

--
-- Name: contact_requests_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE contact_requests_id_seq OWNED BY contact_requests.id;


--
-- Name: contacts; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE contacts (
    id bigint NOT NULL,
    name character varying(100),
    company character varying(100) DEFAULT ''::text NOT NULL,
    address1 character varying(100) DEFAULT ''::text NOT NULL,
    address2 character varying(100) DEFAULT ''::text NOT NULL,
    city character varying(50) DEFAULT ''::text NOT NULL,
    state character varying(50) DEFAULT ''::text NOT NULL,
    postal character varying(20) DEFAULT ''::text NOT NULL,
    country character varying(50) DEFAULT ''::text NOT NULL,
    phone character varying(20) DEFAULT ''::text NOT NULL,
    cell_phone character varying(20) DEFAULT ''::text NOT NULL,
    fax character varying(20) DEFAULT ''::text NOT NULL,
    email character varying(100),
    email_confirmed yes_no DEFAULT 'no'::yes_no NOT NULL,
    local_signup yes_no DEFAULT 'no'::yes_no NOT NULL,
    birthday character varying(4) DEFAULT '0000'::character varying NOT NULL,
    email_list contacts_email_list_type DEFAULT 'no'::contacts_email_list_type NOT NULL,
    email_use_name yes_no DEFAULT 'yes'::yes_no NOT NULL,
    mailing_list yes_no DEFAULT 'no'::yes_no NOT NULL,
    top_size character varying(50) DEFAULT ''::character varying NOT NULL,
    bottom_size character varying(50) DEFAULT ''::character varying NOT NULL,
    create_date date NOT NULL,
    notes text NOT NULL,
    notes_contact text NOT NULL,
    tickler date,
    source text,
    shipping_problems yes_no DEFAULT 'no'::yes_no NOT NULL
);


ALTER TABLE contacts OWNER TO btqcm;

--
-- Name: contacts_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE contacts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE contacts_id_seq OWNER TO btqcm;

--
-- Name: contacts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE contacts_id_seq OWNED BY contacts.id;


--
-- Name: contacts_ml_removes; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE contacts_ml_removes (
    id bigint NOT NULL,
    contact_id bigint NOT NULL,
    remove_date date NOT NULL
);


ALTER TABLE contacts_ml_removes OWNER TO btqcm;

--
-- Name: contacts_ml_removes_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE contacts_ml_removes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE contacts_ml_removes_id_seq OWNER TO btqcm;

--
-- Name: contacts_ml_removes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE contacts_ml_removes_id_seq OWNED BY contacts_ml_removes.id;


--
-- Name: contacts_postcards_sends; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE contacts_postcards_sends (
    id bigint NOT NULL,
    contact_id bigint NOT NULL,
    title text NOT NULL,
    descrip text NOT NULL
);


ALTER TABLE contacts_postcards_sends OWNER TO btqcm;

--
-- Name: contacts_postcards_sends_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE contacts_postcards_sends_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE contacts_postcards_sends_id_seq OWNER TO btqcm;

--
-- Name: contacts_postcards_sends_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE contacts_postcards_sends_id_seq OWNED BY contacts_postcards_sends.id;


--
-- Name: customer_appreciation; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE customer_appreciation (
    id bigint NOT NULL,
    contact_id bigint NOT NULL,
    cust_type order_type NOT NULL,
    season_id bigint NOT NULL,
    current_season_sales numeric(10,2) NOT NULL,
    last_season_sales numeric(10,2) NOT NULL,
    discount integer DEFAULT 10 NOT NULL
);


ALTER TABLE customer_appreciation OWNER TO btqcm;

--
-- Name: customer_appreciation_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE customer_appreciation_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE customer_appreciation_id_seq OWNER TO btqcm;

--
-- Name: customer_appreciation_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE customer_appreciation_id_seq OWNED BY customer_appreciation.id;


--
-- Name: designer_order_item_status; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE designer_order_item_status (
    id bigint NOT NULL,
    designer_order_item_id bigint NOT NULL,
    received_date date,
    received_shipment_item_id bigint,
    item_cxl_date date,
    item_cxl_reason character varying(255),
    order_item_status_id bigint
);


ALTER TABLE designer_order_item_status OWNER TO btqcm;

--
-- Name: designer_order_item_status_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE designer_order_item_status_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE designer_order_item_status_id_seq OWNER TO btqcm;

--
-- Name: designer_order_item_status_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE designer_order_item_status_id_seq OWNED BY designer_order_item_status.id;


--
-- Name: designer_order_items; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE designer_order_items (
    id bigint NOT NULL,
    designer_order_id integer NOT NULL,
    item_breakdown_id bigint NOT NULL,
    item_name character varying(255) NOT NULL,
    item_color_name character varying(255),
    size_name character varying(255),
    quantity integer NOT NULL,
    cost numeric NOT NULL,
    list_cost numeric NOT NULL,
    CONSTRAINT designer_order_items_cost_check CHECK ((cost > (0)::numeric)),
    CONSTRAINT designer_order_items_list_cost_check CHECK ((list_cost > (0)::numeric)),
    CONSTRAINT designer_order_items_quantity_check CHECK ((quantity > 0))
);


ALTER TABLE designer_order_items OWNER TO btqcm;

--
-- Name: designer_order_items_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE designer_order_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE designer_order_items_id_seq OWNER TO btqcm;

--
-- Name: designer_order_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE designer_order_items_id_seq OWNED BY designer_order_items.id;


--
-- Name: designer_orders; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE designer_orders (
    id bigint NOT NULL,
    designer_id bigint NOT NULL,
    designer_po_number character varying(255),
    start_ship_date date NOT NULL,
    cancel_ship_date date NOT NULL,
    payment_terms designer_terms DEFAULT 'Net30'::designer_terms,
    discount_pct smallint DEFAULT 0,
    discount_dollars numeric DEFAULT 0,
    free_shipping boolean DEFAULT false NOT NULL,
    placed_date date,
    placed_by_employee_id bigint,
    placed_method communication_methods,
    confirmed_date date,
    confirmed_by character varying(255),
    confirmed_method communication_methods,
    confirmation_received_by_employee_id bigint,
    cxl_date date,
    cxl_reason character varying(255),
    cxl_notes text,
    enter_dt timestamp without time zone NOT NULL,
    entered_by_employee_id bigint NOT NULL,
    description character varying(255) NOT NULL,
    notes text,
    approved_by_employee_id bigint,
    approved_dt timestamp without time zone,
    placed_notes text,
    placed_with character varying(255),
    cxl_by_employee_id bigint,
    CONSTRAINT designer_orders_discount_pct_check CHECK (((discount_pct >= 0) AND (discount_pct <= 100)))
);


ALTER TABLE designer_orders OWNER TO btqcm;

--
-- Name: designer_orders_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE designer_orders_id_seq
    START WITH 32468
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE designer_orders_id_seq OWNER TO btqcm;

--
-- Name: designer_orders_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE designer_orders_id_seq OWNED BY designer_orders.id;


--
-- Name: designer_search_aliases; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE designer_search_aliases (
    id bigint NOT NULL,
    alias character varying NOT NULL
);


ALTER TABLE designer_search_aliases OWNER TO btqcm;

--
-- Name: email_contacts; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE email_contacts (
    id bigint NOT NULL,
    email_id bigint NOT NULL,
    contact_id bigint NOT NULL,
    send_dt timestamp without time zone DEFAULT '2015-01-01 00:00:00'::timestamp without time zone NOT NULL,
    email json,
    request_id bigint,
    is_test boolean DEFAULT false NOT NULL
);


ALTER TABLE email_contacts OWNER TO btqcm;

--
-- Name: email_contacts_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE email_contacts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE email_contacts_id_seq OWNER TO btqcm;

--
-- Name: email_contacts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE email_contacts_id_seq OWNED BY email_contacts.id;


--
-- Name: email_images; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE email_images (
    id bigint NOT NULL,
    file text DEFAULT ''::text NOT NULL
);


ALTER TABLE email_images OWNER TO btqcm;

--
-- Name: email_images_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE email_images_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE email_images_id_seq OWNER TO btqcm;

--
-- Name: email_images_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE email_images_id_seq OWNED BY email_images.id;


--
-- Name: email_outbox; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE email_outbox (
    id bigint NOT NULL,
    email json NOT NULL,
    email_id bigint,
    contact_id bigint,
    request_id bigint,
    inserted_dt timestamp without time zone DEFAULT now() NOT NULL,
    status character varying DEFAULT 'ready'::character varying NOT NULL,
    sending_dt timestamp without time zone,
    error text DEFAULT ''::text NOT NULL
);


ALTER TABLE email_outbox OWNER TO btqcm;

--
-- Name: TABLE email_outbox; Type: COMMENT; Schema: public; Owner: btqcm
--

COMMENT ON TABLE email_outbox IS 'This is a test comment';


--
-- Name: email_outbox_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE email_outbox_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE email_outbox_id_seq OWNER TO btqcm;

--
-- Name: email_outbox_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE email_outbox_id_seq OWNED BY email_outbox.id;


--
-- Name: email_queue; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE email_queue (
    id integer NOT NULL,
    email_id bigint NOT NULL,
    contact_id bigint NOT NULL,
    dt timestamp without time zone NOT NULL
);


ALTER TABLE email_queue OWNER TO btqcm;

--
-- Name: email_queue_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE email_queue_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE email_queue_id_seq OWNER TO btqcm;

--
-- Name: email_queue_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE email_queue_id_seq OWNED BY email_queue.id;


--
-- Name: email_send_requests; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE email_send_requests (
    id bigint NOT NULL,
    note text NOT NULL,
    request_dt timestamp without time zone DEFAULT now() NOT NULL,
    notify_when_done boolean DEFAULT false NOT NULL,
    num integer DEFAULT '-1'::integer NOT NULL
);


ALTER TABLE email_send_requests OWNER TO btqcm;

--
-- Name: email_send_requests_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE email_send_requests_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE email_send_requests_id_seq OWNER TO btqcm;

--
-- Name: email_send_requests_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE email_send_requests_id_seq OWNED BY email_send_requests.id;


--
-- Name: emails; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE emails (
    id bigint NOT NULL,
    create_dt timestamp without time zone NOT NULL,
    ad_code_id bigint NOT NULL,
    sent_to_deprecated character varying(100) NOT NULL,
    send_contact_type send_contact_type NOT NULL,
    on_email_list on_email_list_type DEFAULT 'yes'::on_email_list_type NOT NULL,
    targeted_des character varying(255) NOT NULL,
    excludes character varying(255) NOT NULL,
    email_type email_text_type DEFAULT 'html'::email_text_type NOT NULL,
    subject character varying(100) NOT NULL,
    middle_html text NOT NULL,
    landing_html text NOT NULL,
    logo_link character varying(100) NOT NULL,
    plain_text text NOT NULL,
    from_email character varying(50) NOT NULL,
    from_name character varying(50) NOT NULL,
    reply_to_email character varying(50) NOT NULL,
    purpose character varying(255)
);


ALTER TABLE emails OWNER TO btqcm;

--
-- Name: emails_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE emails_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE emails_id_seq OWNER TO btqcm;

--
-- Name: emails_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE emails_id_seq OWNED BY emails.id;


--
-- Name: emails_sent_xx; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE emails_sent_xx (
    id bigint NOT NULL,
    email json NOT NULL,
    email_id bigint,
    contact_id bigint,
    request_id bigint,
    sent_dt timestamp without time zone DEFAULT now() NOT NULL
);


ALTER TABLE emails_sent_xx OWNER TO btqcm;

--
-- Name: emails_sent_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE emails_sent_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE emails_sent_id_seq OWNER TO btqcm;

--
-- Name: emails_sent_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE emails_sent_id_seq OWNED BY emails_sent_xx.id;


--
-- Name: employees; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE employees (
    id bigint NOT NULL,
    fname character varying(30) DEFAULT ''::character varying NOT NULL,
    mi character(1) DEFAULT ''::bpchar NOT NULL,
    lname character varying(30) DEFAULT ''::character varying NOT NULL,
    soc_sec character varying(20) DEFAULT ''::character varying NOT NULL,
    dob date,
    address character varying(50) DEFAULT ''::text NOT NULL,
    city character varying(50) DEFAULT ''::text NOT NULL,
    state character(2) DEFAULT ''::bpchar NOT NULL,
    zip character varying(10) DEFAULT ''::text NOT NULL,
    phone character varying(20) DEFAULT ''::text NOT NULL,
    email character varying(100) DEFAULT ''::text NOT NULL,
    pay_type pay_type DEFAULT 'hourly'::pay_type NOT NULL,
    starting_pay numeric(10,2) NOT NULL,
    pay_rate numeric(10,2) DEFAULT 0.00 NOT NULL,
    starting_position character varying(50) DEFAULT 'sales'::text NOT NULL,
    "position" character varying(50) NOT NULL,
    marital_status marital_status DEFAULT 'single'::marital_status NOT NULL,
    fed_additional_withholding numeric(3,0) NOT NULL,
    fed_allowances smallint DEFAULT 0 NOT NULL,
    fed_exempt yes_no DEFAULT 'no'::yes_no NOT NULL,
    state_code character(1) DEFAULT ''::bpchar NOT NULL,
    vacations_paid text NOT NULL,
    healthcare_deduction numeric(5,2) NOT NULL,
    start_date date NOT NULL,
    end_date date,
    state_additional_withholding numeric(3,0)
);


ALTER TABLE employees OWNER TO btqcm;

--
-- Name: employees_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE employees_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE employees_id_seq OWNER TO btqcm;

--
-- Name: employees_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE employees_id_seq OWNED BY employees.id;


--
-- Name: general_journal; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE general_journal (
    id bigint NOT NULL,
    trans_date date NOT NULL,
    amount numeric(10,2) NOT NULL,
    notes text DEFAULT ''::text NOT NULL,
    ref_number text DEFAULT ''::text NOT NULL,
    account_debit bigint NOT NULL,
    account_credit bigint NOT NULL
);


ALTER TABLE general_journal OWNER TO btqcm;

--
-- Name: general_journal_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE general_journal_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE general_journal_id_seq OWNER TO btqcm;

--
-- Name: general_journal_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE general_journal_id_seq OWNED BY general_journal.id;


--
-- Name: gift_certificates; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE gift_certificates (
    id bigint NOT NULL,
    order_item_id bigint,
    gc_number character varying(20) DEFAULT ''::text NOT NULL,
    original_amount numeric(7,2) DEFAULT 0.00 NOT NULL,
    balance numeric(7,2) NOT NULL,
    create_date date NOT NULL,
    notes text NOT NULL,
    redemption_code character(8) NOT NULL,
    to_name character varying(100) NOT NULL,
    to_address character varying(100) NOT NULL,
    to_city character varying(50) NOT NULL,
    to_state character varying(50) NOT NULL,
    to_country character varying(50) NOT NULL,
    to_postal character varying(20) NOT NULL,
    from_name character varying(100) NOT NULL,
    message text NOT NULL,
    status gc_status DEFAULT 'valid'::gc_status NOT NULL
);


ALTER TABLE gift_certificates OWNER TO btqcm;

--
-- Name: gift_certificates_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE gift_certificates_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE gift_certificates_id_seq OWNER TO btqcm;

--
-- Name: gift_certificates_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE gift_certificates_id_seq OWNED BY gift_certificates.id;


--
-- Name: gtd_someday_maybe; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE gtd_someday_maybe (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    created timestamp without time zone NOT NULL
);


ALTER TABLE gtd_someday_maybe OWNER TO btqcm;

--
-- Name: gtd_someday_maybe_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE gtd_someday_maybe_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE gtd_someday_maybe_id_seq OWNER TO btqcm;

--
-- Name: gtd_someday_maybe_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE gtd_someday_maybe_id_seq OWNED BY gtd_someday_maybe.id;


--
-- Name: inventory_adjustments; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE inventory_adjustments (
    id bigint NOT NULL,
    item_breakdown_id bigint NOT NULL,
    qty integer NOT NULL,
    cost numeric(8,2) NOT NULL,
    action inventory_adjustment_action DEFAULT 'add'::inventory_adjustment_action NOT NULL,
    reason text DEFAULT ''::text NOT NULL,
    dt timestamp without time zone NOT NULL,
    CONSTRAINT inventory_adjustments_qty_check CHECK ((qty <> 0))
);


ALTER TABLE inventory_adjustments OWNER TO btqcm;

--
-- Name: inventory_adjustments_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE inventory_adjustments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE inventory_adjustments_id_seq OWNER TO btqcm;

--
-- Name: inventory_adjustments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE inventory_adjustments_id_seq OWNED BY inventory_adjustments.id;


--
-- Name: inventory_ais_reports; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE inventory_ais_reports (
    id bigint NOT NULL,
    report_date date NOT NULL,
    item_id bigint NOT NULL,
    time_period_months ais_report_time_period DEFAULT '3'::ais_report_time_period NOT NULL,
    num_units_sold integer NOT NULL,
    return_pct numeric(3,0) NOT NULL,
    num_units_inst integer NOT NULL,
    num_units_spec integer NOT NULL,
    retail_amount numeric(10,2) NOT NULL,
    profit_pct numeric(3,0) NOT NULL,
    num_units_colors text NOT NULL,
    num_units_sizes text NOT NULL,
    ais_store_colors text NOT NULL,
    ais_web_colors text NOT NULL,
    sales text NOT NULL
);


ALTER TABLE inventory_ais_reports OWNER TO btqcm;

--
-- Name: inventory_ais_reports_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE inventory_ais_reports_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE inventory_ais_reports_id_seq OWNER TO btqcm;

--
-- Name: inventory_ais_reports_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE inventory_ais_reports_id_seq OWNED BY inventory_ais_reports.id;


--
-- Name: inventory_categories; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE inventory_categories (
    id bigint NOT NULL,
    name text DEFAULT ''::text NOT NULL,
    description text NOT NULL,
    taxable yes_no DEFAULT 'yes'::yes_no NOT NULL,
    taxable_under50 yes_no DEFAULT 'no'::yes_no NOT NULL,
    name_image text DEFAULT ''::text NOT NULL,
    sort_order integer DEFAULT 0 NOT NULL,
    status_web active_or_inactive DEFAULT 'active'::active_or_inactive NOT NULL
);


ALTER TABLE inventory_categories OWNER TO btqcm;

--
-- Name: inventory_categories_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE inventory_categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE inventory_categories_id_seq OWNER TO btqcm;

--
-- Name: inventory_categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE inventory_categories_id_seq OWNED BY inventory_categories.id;


--
-- Name: inventory_cross_sell; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE inventory_cross_sell (
    id bigint NOT NULL,
    item_id bigint NOT NULL,
    cross_item_id bigint NOT NULL
);


ALTER TABLE inventory_cross_sell OWNER TO btqcm;

--
-- Name: inventory_cross_sell_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE inventory_cross_sell_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE inventory_cross_sell_id_seq OWNER TO btqcm;

--
-- Name: inventory_cross_sell_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE inventory_cross_sell_id_seq OWNED BY inventory_cross_sell.id;


--
-- Name: inventory_groupings; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE inventory_groupings (
    id bigint NOT NULL,
    designer_id bigint NOT NULL,
    season_id bigint,
    title text DEFAULT ''::text NOT NULL,
    description text NOT NULL,
    sortorder integer DEFAULT 0 NOT NULL,
    status active_or_inactive DEFAULT 'active'::active_or_inactive NOT NULL
);


ALTER TABLE inventory_groupings OWNER TO btqcm;

--
-- Name: inventory_groupings_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE inventory_groupings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE inventory_groupings_id_seq OWNER TO btqcm;

--
-- Name: inventory_groupings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE inventory_groupings_id_seq OWNED BY inventory_groupings.id;


--
-- Name: inventory_item_breakdown; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE inventory_item_breakdown (
    id bigint NOT NULL,
    item_id bigint NOT NULL,
    item_color_id bigint,
    size_id bigint,
    qty_instock integer DEFAULT 0 NOT NULL
);


ALTER TABLE inventory_item_breakdown OWNER TO btqcm;

--
-- Name: inventory_item_breakdown_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE inventory_item_breakdown_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE inventory_item_breakdown_id_seq OWNER TO btqcm;

--
-- Name: inventory_item_breakdown_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE inventory_item_breakdown_id_seq OWNED BY inventory_item_breakdown.id;


--
-- Name: inventory_item_categories; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE inventory_item_categories (
    id bigint NOT NULL,
    item_id bigint NOT NULL,
    category_id bigint NOT NULL
);


ALTER TABLE inventory_item_categories OWNER TO btqcm;

--
-- Name: inventory_item_categories_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE inventory_item_categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE inventory_item_categories_id_seq OWNER TO btqcm;

--
-- Name: inventory_item_categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE inventory_item_categories_id_seq OWNED BY inventory_item_categories.id;


--
-- Name: inventory_item_colors; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE inventory_item_colors (
    id bigint NOT NULL,
    item_id bigint NOT NULL,
    color character varying(50) NOT NULL,
    color_hex character varying(6) DEFAULT ''::character varying NOT NULL,
    color_description text DEFAULT ''::text NOT NULL,
    swatch text DEFAULT ''::text NOT NULL,
    swatch_thumb text DEFAULT ''::text NOT NULL,
    ais_web yes_no DEFAULT 'no'::yes_no NOT NULL,
    ais_store yes_no DEFAULT 'no'::yes_no NOT NULL,
    available_date date,
    sale_price numeric(8,2),
    color_alias character varying(50)
);


ALTER TABLE inventory_item_colors OWNER TO btqcm;

--
-- Name: inventory_item_colors_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE inventory_item_colors_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE inventory_item_colors_id_seq OWNER TO btqcm;

--
-- Name: inventory_item_colors_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE inventory_item_colors_id_seq OWNED BY inventory_item_colors.id;


--
-- Name: inventory_item_images; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE inventory_item_images (
    id bigint NOT NULL,
    item_id bigint NOT NULL,
    imagefile text DEFAULT ''::text NOT NULL,
    caption text DEFAULT ''::text NOT NULL,
    color text NOT NULL,
    show_xlarge yes_no DEFAULT 'yes'::yes_no NOT NULL,
    imageorder smallint DEFAULT 0 NOT NULL,
    statusimg active_or_inactive DEFAULT 'active'::active_or_inactive NOT NULL
);


ALTER TABLE inventory_item_images OWNER TO btqcm;

--
-- Name: inventory_item_images_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE inventory_item_images_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE inventory_item_images_id_seq OWNER TO btqcm;

--
-- Name: inventory_item_images_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE inventory_item_images_id_seq OWNED BY inventory_item_images.id;


--
-- Name: inventory_items; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE inventory_items (
    id bigint NOT NULL,
    style_number character varying(50) NOT NULL,
    season_id bigint,
    grouping_id bigint,
    name character varying(255) NOT NULL,
    short_name character varying(21) NOT NULL,
    special_text character varying(255) NOT NULL,
    description text NOT NULL,
    keywords character varying(255) DEFAULT ''::text NOT NULL,
    designer_id bigint,
    fabrication character varying(255) DEFAULT ''::text NOT NULL,
    fabric_name character varying(255) NOT NULL,
    measurements text NOT NULL,
    care character varying(255) DEFAULT ''::text NOT NULL,
    cost numeric(8,2) NOT NULL,
    price numeric(8,2) DEFAULT 0.00 NOT NULL,
    sale_price numeric(8,2) DEFAULT 0.00 NOT NULL,
    price_web numeric(8,2) DEFAULT 0.00 NOT NULL,
    sale_price_web numeric(8,2) DEFAULT 0.00 NOT NULL,
    size_scale_id bigint,
    size_chart_img character varying(255) NOT NULL,
    status_web active_or_inactive DEFAULT 'inactive'::active_or_inactive,
    preorder_cutoff_date date,
    available_date date,
    enter_date date NOT NULL,
    sort_order integer DEFAULT 0 NOT NULL,
    CONSTRAINT sale_price_check CHECK ((sale_price <= price)),
    CONSTRAINT sale_price_web_check CHECK ((sale_price_web <= price_web))
);


ALTER TABLE inventory_items OWNER TO btqcm;

--
-- Name: inventory_items_groupings; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE inventory_items_groupings (
    id bigint NOT NULL,
    item_id bigint NOT NULL,
    grouping_id bigint NOT NULL,
    sortorder integer DEFAULT 0 NOT NULL
);


ALTER TABLE inventory_items_groupings OWNER TO btqcm;

--
-- Name: inventory_items_groupings_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE inventory_items_groupings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE inventory_items_groupings_id_seq OWNER TO btqcm;

--
-- Name: inventory_items_groupings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE inventory_items_groupings_id_seq OWNED BY inventory_items_groupings.id;


--
-- Name: inventory_items_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE inventory_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE inventory_items_id_seq OWNER TO btqcm;

--
-- Name: inventory_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE inventory_items_id_seq OWNED BY inventory_items.id;


--
-- Name: inventory_items_popular; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE inventory_items_popular (
    id bigint NOT NULL,
    item_id bigint NOT NULL
);


ALTER TABLE inventory_items_popular OWNER TO btqcm;

--
-- Name: inventory_items_popular_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE inventory_items_popular_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE inventory_items_popular_id_seq OWNER TO btqcm;

--
-- Name: inventory_items_popular_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE inventory_items_popular_id_seq OWNED BY inventory_items_popular.id;


--
-- Name: inventory_reports; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE inventory_reports (
    id bigint NOT NULL,
    create_dt timestamp without time zone NOT NULL,
    total_cost numeric(8,2) NOT NULL,
    total_retail numeric(8,2) NOT NULL,
    total_num integer NOT NULL,
    fullprice_cost numeric(8,2) NOT NULL,
    fullprice_retail numeric(8,2) NOT NULL,
    fullprice_num integer NOT NULL,
    onsale_cost numeric(8,2) NOT NULL,
    onsale_retail numeric(8,2) NOT NULL,
    onsale_num integer NOT NULL,
    by_season text NOT NULL,
    by_category text NOT NULL,
    by_designer text NOT NULL,
    comments text NOT NULL
);


ALTER TABLE inventory_reports OWNER TO btqcm;

--
-- Name: inventory_reports_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE inventory_reports_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE inventory_reports_id_seq OWNER TO btqcm;

--
-- Name: inventory_reports_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE inventory_reports_id_seq OWNED BY inventory_reports.id;


--
-- Name: inventory_search_view; Type: MATERIALIZED VIEW; Schema: public; Owner: btqcm
--

CREATE MATERIALIZED VIEW inventory_search_view AS
 SELECT i.id,
    (((setweight(to_tsvector((i.name)::text), 'A'::"char") || setweight(designer_search_vector(i.designer_id), 'B'::"char")) || to_tsvector((i.keywords)::text)) || to_tsvector(i.description)) AS ts_vec
   FROM inventory_items i
  WITH NO DATA;


ALTER TABLE inventory_search_view OWNER TO btqcm;

--
-- Name: inventory_seasons; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE inventory_seasons (
    id bigint NOT NULL,
    season character varying(10) DEFAULT ''::text NOT NULL
);


ALTER TABLE inventory_seasons OWNER TO btqcm;

--
-- Name: inventory_seasons_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE inventory_seasons_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE inventory_seasons_id_seq OWNER TO btqcm;

--
-- Name: inventory_seasons_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE inventory_seasons_id_seq OWNED BY inventory_seasons.id;


--
-- Name: inventory_shipment_items; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE inventory_shipment_items (
    id bigint NOT NULL,
    shipment_id bigint NOT NULL,
    item_id bigint NOT NULL,
    item_breakdown_id bigint NOT NULL,
    quantity integer DEFAULT 0 NOT NULL,
    unit_cost numeric(8,2) DEFAULT 0.00 NOT NULL
);


ALTER TABLE inventory_shipment_items OWNER TO btqcm;

--
-- Name: inventory_shipment_items_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE inventory_shipment_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE inventory_shipment_items_id_seq OWNER TO btqcm;

--
-- Name: inventory_shipment_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE inventory_shipment_items_id_seq OWNED BY inventory_shipment_items.id;


--
-- Name: inventory_shipments; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE inventory_shipments (
    id bigint NOT NULL,
    designer_id bigint NOT NULL,
    receive_date date NOT NULL,
    received_by text DEFAULT ''::text NOT NULL,
    enter_date date NOT NULL,
    order_number text DEFAULT ''::text NOT NULL,
    packing_slip_number text DEFAULT ''::text NOT NULL,
    invoice_number text DEFAULT ''::text NOT NULL,
    invoice_due_date date,
    invoice_paid_date date,
    notes text NOT NULL
);


ALTER TABLE inventory_shipments OWNER TO btqcm;

--
-- Name: inventory_shipments_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE inventory_shipments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE inventory_shipments_id_seq OWNER TO btqcm;

--
-- Name: inventory_shipments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE inventory_shipments_id_seq OWNED BY inventory_shipments.id;


--
-- Name: inventory_size_scale_sizes; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE inventory_size_scale_sizes (
    id bigint NOT NULL,
    size_scale_id bigint NOT NULL,
    size_id bigint NOT NULL,
    sort_order integer DEFAULT 0 NOT NULL
);


ALTER TABLE inventory_size_scale_sizes OWNER TO btqcm;

--
-- Name: inventory_size_scale_sizes_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE inventory_size_scale_sizes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE inventory_size_scale_sizes_id_seq OWNER TO btqcm;

--
-- Name: inventory_size_scale_sizes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE inventory_size_scale_sizes_id_seq OWNED BY inventory_size_scale_sizes.id;


--
-- Name: inventory_size_scales; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE inventory_size_scales (
    id bigint NOT NULL,
    name text NOT NULL,
    description text NOT NULL,
    chart_file text DEFAULT ''::text,
    sort_order integer DEFAULT 0 NOT NULL
);


ALTER TABLE inventory_size_scales OWNER TO btqcm;

--
-- Name: inventory_size_scales_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE inventory_size_scales_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE inventory_size_scales_id_seq OWNER TO btqcm;

--
-- Name: inventory_size_scales_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE inventory_size_scales_id_seq OWNED BY inventory_size_scales.id;


--
-- Name: inventory_sizes; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE inventory_sizes (
    id bigint NOT NULL,
    size character varying(10) NOT NULL
);


ALTER TABLE inventory_sizes OWNER TO btqcm;

--
-- Name: inventory_sizes_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE inventory_sizes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE inventory_sizes_id_seq OWNER TO btqcm;

--
-- Name: inventory_sizes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE inventory_sizes_id_seq OWNED BY inventory_sizes.id;


--
-- Name: invoice_shipments; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE invoice_shipments (
    id bigint NOT NULL,
    invoice_id bigint NOT NULL,
    shipment_id bigint NOT NULL
);


ALTER TABLE invoice_shipments OWNER TO btqcm;

--
-- Name: invoice_shipments_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE invoice_shipments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE invoice_shipments_id_seq OWNER TO btqcm;

--
-- Name: invoice_shipments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE invoice_shipments_id_seq OWNED BY invoice_shipments.id;


--
-- Name: invoices; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE invoices (
    id bigint NOT NULL,
    invoice_number text NOT NULL,
    terms invoice_terms DEFAULT 'net 30'::invoice_terms NOT NULL,
    items_amount numeric(8,2) DEFAULT 0.00 NOT NULL,
    shipping_amount numeric(6,2) DEFAULT 0.00 NOT NULL,
    due_date date NOT NULL,
    paid_date date,
    vendor_id bigint NOT NULL,
    factor_id bigint NOT NULL,
    designer_id bigint NOT NULL
);


ALTER TABLE invoices OWNER TO btqcm;

--
-- Name: invoices_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE invoices_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE invoices_id_seq OWNER TO btqcm;

--
-- Name: invoices_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE invoices_id_seq OWNED BY invoices.id;


--
-- Name: licenses; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE licenses (
    id bigint NOT NULL,
    authority text DEFAULT ''::text NOT NULL,
    license_name text DEFAULT ''::text NOT NULL,
    licensce_number text DEFAULT ''::text NOT NULL,
    issue_date date,
    expiration_date date,
    notes text NOT NULL
);


ALTER TABLE licenses OWNER TO btqcm;

--
-- Name: licenses_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE licenses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE licenses_id_seq OWNER TO btqcm;

--
-- Name: licenses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE licenses_id_seq OWNED BY licenses.id;


--
-- Name: news; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE news (
    id bigint NOT NULL,
    event news_event DEFAULT 'new web item'::news_event NOT NULL,
    event_date date NOT NULL,
    event_title character varying(100) NOT NULL,
    event_text text NOT NULL,
    event_image character varying(255) NOT NULL,
    ref_id bigint NOT NULL,
    status active_or_inactive DEFAULT 'active'::active_or_inactive NOT NULL
);


ALTER TABLE news OWNER TO btqcm;

--
-- Name: news_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE news_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE news_id_seq OWNER TO btqcm;

--
-- Name: news_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE news_id_seq OWNED BY news.id;


--
-- Name: news_images; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE news_images (
    id bigint NOT NULL,
    file text DEFAULT ''::text NOT NULL
);


ALTER TABLE news_images OWNER TO btqcm;

--
-- Name: news_images_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE news_images_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE news_images_id_seq OWNER TO btqcm;

--
-- Name: news_images_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE news_images_id_seq OWNED BY news_images.id;


--
-- Name: order_item_status; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE order_item_status (
    id bigint NOT NULL,
    order_item_id bigint NOT NULL,
    order_item_status order_item_status_type DEFAULT 'sto'::order_item_status_type NOT NULL,
    bck_date date,
    bck_expected_date date,
    order_item_delivery_date date,
    shipped_from shipped_from,
    order_item_return_date date,
    order_item_notes text NOT NULL
);


ALTER TABLE order_item_status OWNER TO btqcm;

--
-- Name: order_item_status_history; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE order_item_status_history (
    id bigint NOT NULL,
    order_item_status_id bigint NOT NULL,
    order_item_id bigint NOT NULL,
    change_dt timestamp without time zone NOT NULL,
    order_item_status order_item_status_type NOT NULL,
    bck_date date,
    bck_expected_date date,
    order_item_delivery_date date,
    shipped_from shipped_from,
    order_item_return_date date,
    order_item_notes text NOT NULL
);


ALTER TABLE order_item_status_history OWNER TO btqcm;

--
-- Name: order_item_status_history_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE order_item_status_history_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE order_item_status_history_id_seq OWNER TO btqcm;

--
-- Name: order_item_status_history_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE order_item_status_history_id_seq OWNED BY order_item_status_history.id;


--
-- Name: order_item_status_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE order_item_status_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE order_item_status_id_seq OWNER TO btqcm;

--
-- Name: order_item_status_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE order_item_status_id_seq OWNED BY order_item_status.id;


--
-- Name: order_items; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE order_items (
    id bigint NOT NULL,
    order_id bigint NOT NULL,
    item_id bigint NOT NULL,
    item_action order_item_action DEFAULT 'purchase'::order_item_action NOT NULL,
    item_price numeric(8,2) DEFAULT 0.00 NOT NULL,
    item_tax numeric(6,2) DEFAULT 0.00 NOT NULL,
    item_qty integer DEFAULT 0 NOT NULL,
    item_bd_id bigint,
    item_color text DEFAULT ''::text NOT NULL,
    item_size text DEFAULT ''::text NOT NULL,
    item_designer_name text DEFAULT ''::text NOT NULL,
    item_name text DEFAULT ''::text NOT NULL,
    item_description text NOT NULL,
    final_sale yes_no DEFAULT 'no'::yes_no NOT NULL,
    item_list_price numeric(8,2) DEFAULT 0.00 NOT NULL,
    item_order_dt timestamp without time zone NOT NULL
);


ALTER TABLE order_items OWNER TO btqcm;

--
-- Name: order_items_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE order_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE order_items_id_seq OWNER TO btqcm;

--
-- Name: order_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE order_items_id_seq OWNED BY order_items.id;


--
-- Name: order_return_items; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE order_return_items (
    id bigint NOT NULL,
    order_return_id bigint NOT NULL,
    order_item_status_id bigint NOT NULL
);


ALTER TABLE order_return_items OWNER TO btqcm;

--
-- Name: order_return_items_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE order_return_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE order_return_items_id_seq OWNER TO btqcm;

--
-- Name: order_return_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE order_return_items_id_seq OWNED BY order_return_items.id;


--
-- Name: order_returns; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE order_returns (
    id bigint NOT NULL,
    order_id bigint NOT NULL,
    receive_date date NOT NULL,
    received_by bigint
);


ALTER TABLE order_returns OWNER TO btqcm;

--
-- Name: order_returns_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE order_returns_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE order_returns_id_seq OWNER TO btqcm;

--
-- Name: order_returns_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE order_returns_id_seq OWNED BY order_returns.id;


--
-- Name: order_shipment_items; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE order_shipment_items (
    id bigint NOT NULL,
    order_shipment_id bigint NOT NULL,
    order_item_status_id bigint NOT NULL
);


ALTER TABLE order_shipment_items OWNER TO btqcm;

--
-- Name: order_shipment_items_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE order_shipment_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE order_shipment_items_id_seq OWNER TO btqcm;

--
-- Name: order_shipment_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE order_shipment_items_id_seq OWNED BY order_shipment_items.id;


--
-- Name: order_shipments; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE order_shipments (
    id bigint NOT NULL,
    order_id bigint NOT NULL,
    shipped_from shipping_locations NOT NULL,
    carrier shipping_carriers,
    tracking_number character varying(100),
    price numeric(8,2),
    cost numeric(8,2),
    ship_date date NOT NULL,
    deliver_date date,
    by_employee_id bigint,
    shipping_method_id integer
);


ALTER TABLE order_shipments OWNER TO btqcm;

--
-- Name: order_shipments_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE order_shipments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE order_shipments_id_seq OWNER TO btqcm;

--
-- Name: order_shipments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE order_shipments_id_seq OWNED BY order_shipments.id;


--
-- Name: orders; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE orders (
    id bigint NOT NULL,
    order_dt timestamp without time zone NOT NULL,
    order_type order_type DEFAULT 'web'::order_type NOT NULL,
    contact_id bigint,
    store_receipt_number text DEFAULT ''::text NOT NULL,
    ship_amount numeric(8,2) DEFAULT 0.00 NOT NULL,
    ship_method text DEFAULT ''::text NOT NULL,
    ship_name text DEFAULT ''::text NOT NULL,
    ship_company text DEFAULT ''::text NOT NULL,
    ship_address1 text DEFAULT ''::text NOT NULL,
    ship_address2 text DEFAULT ''::text NOT NULL,
    ship_city text DEFAULT ''::text NOT NULL,
    ship_state text DEFAULT ''::text NOT NULL,
    ship_country text DEFAULT ''::text NOT NULL,
    ship_postal text DEFAULT ''::text NOT NULL,
    ship_phone text DEFAULT ''::text NOT NULL,
    salesperson1 text DEFAULT ''::text NOT NULL,
    salesperson2 text DEFAULT ''::text NOT NULL,
    checkoutperson text NOT NULL,
    ad_code bigint,
    promotion_id bigint,
    comments_customer text NOT NULL,
    notes text NOT NULL,
    tickler date,
    order_status order_status DEFAULT 'open'::order_status NOT NULL
);


ALTER TABLE orders OWNER TO btqcm;

--
-- Name: orders_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE orders_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE orders_id_seq OWNER TO btqcm;

--
-- Name: orders_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE orders_id_seq OWNED BY orders.id;


--
-- Name: orders_transactions; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE orders_transactions (
    id bigint NOT NULL,
    order_id bigint NOT NULL,
    trans_dt timestamp without time zone NOT NULL,
    trans_type order_transaction_type DEFAULT 'payment'::order_transaction_type NOT NULL,
    amount numeric(10,2) DEFAULT 0.00 NOT NULL,
    method transaction_method DEFAULT 'credit card'::transaction_method NOT NULL,
    notes text,
    status order_transaction_status DEFAULT 'closed'::order_transaction_status NOT NULL,
    amount_items numeric(10,2) DEFAULT 0.00,
    amount_gc numeric(10,2) DEFAULT 0.00,
    amount_shipping numeric(6,2) DEFAULT 0.00,
    amount_tax numeric(8,2) DEFAULT 0.00,
    amount_deposit numeric(8,2) DEFAULT 0.00,
    amount_restocking_fee numeric(8,2) DEFAULT 0.00,
    amount_misc numeric(8,2) DEFAULT 0.00
);


ALTER TABLE orders_transactions OWNER TO btqcm;

--
-- Name: orders_transactions_cc; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE orders_transactions_cc (
    id bigint NOT NULL,
    transactions_id bigint NOT NULL,
    cc_type cc_type DEFAULT 'M'::cc_type,
    cc_exp character varying(4) DEFAULT ''::character varying NOT NULL,
    cc_name text DEFAULT ''::text NOT NULL,
    cc_street text DEFAULT ''::text NOT NULL,
    cc_postal text DEFAULT ''::text NOT NULL,
    cc_gateway cc_gateway DEFAULT 'moneytree'::cc_gateway,
    gateway_action gateway_action DEFAULT 'AUTH_CAPTURE'::gateway_action NOT NULL,
    gateway_ref_trans_id text DEFAULT ''::text NOT NULL,
    gateway_trans_id text DEFAULT ''::text NOT NULL,
    gateway_approval_code text DEFAULT ''::text NOT NULL,
    gateway_avs_code character(1) DEFAULT ''::bpchar NOT NULL,
    gateway_cvv2_code character(1) DEFAULT ''::bpchar NOT NULL,
    gateway_cardholder_code character(1) DEFAULT ''::bpchar NOT NULL,
    gateway_raw_data text DEFAULT ''::text NOT NULL,
    status_cctr status_cctr DEFAULT 'CAPT'::status_cctr NOT NULL,
    capture_date timestamp without time zone,
    contact_authnet_payment_profile_id bigint,
    cc_num_last4 character(4)
);


ALTER TABLE orders_transactions_cc OWNER TO btqcm;

--
-- Name: orders_transactions_cc_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE orders_transactions_cc_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE orders_transactions_cc_id_seq OWNER TO btqcm;

--
-- Name: orders_transactions_cc_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE orders_transactions_cc_id_seq OWNED BY orders_transactions_cc.id;


--
-- Name: orders_transactions_deposit_items; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE orders_transactions_deposit_items (
    id bigint NOT NULL,
    transactions_id bigint NOT NULL,
    order_item_status_id bigint NOT NULL,
    deposit_amount numeric(8,2)
);


ALTER TABLE orders_transactions_deposit_items OWNER TO btqcm;

--
-- Name: orders_transactions_deposit_items_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE orders_transactions_deposit_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE orders_transactions_deposit_items_id_seq OWNER TO btqcm;

--
-- Name: orders_transactions_deposit_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE orders_transactions_deposit_items_id_seq OWNED BY orders_transactions_deposit_items.id;


--
-- Name: orders_transactions_gc; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE orders_transactions_gc (
    id bigint NOT NULL,
    transactions_id bigint NOT NULL,
    gift_certificates_id bigint
);


ALTER TABLE orders_transactions_gc OWNER TO btqcm;

--
-- Name: orders_transactions_gc_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE orders_transactions_gc_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE orders_transactions_gc_id_seq OWNER TO btqcm;

--
-- Name: orders_transactions_gc_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE orders_transactions_gc_id_seq OWNED BY orders_transactions_gc.id;


--
-- Name: orders_transactions_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE orders_transactions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE orders_transactions_id_seq OWNER TO btqcm;

--
-- Name: orders_transactions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE orders_transactions_id_seq OWNED BY orders_transactions.id;


--
-- Name: orders_transactions_items; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE orders_transactions_items (
    id bigint NOT NULL,
    transactions_id bigint NOT NULL,
    order_item_status_id bigint NOT NULL
);


ALTER TABLE orders_transactions_items OWNER TO btqcm;

--
-- Name: orders_transactions_items_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE orders_transactions_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE orders_transactions_items_id_seq OWNER TO btqcm;

--
-- Name: orders_transactions_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE orders_transactions_items_id_seq OWNED BY orders_transactions_items.id;


--
-- Name: orders_transactions_sc; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE orders_transactions_sc (
    id bigint NOT NULL,
    transactions_id bigint NOT NULL,
    store_credits_id bigint NOT NULL
);


ALTER TABLE orders_transactions_sc OWNER TO btqcm;

--
-- Name: orders_transactions_sc_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE orders_transactions_sc_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE orders_transactions_sc_id_seq OWNER TO btqcm;

--
-- Name: orders_transactions_sc_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE orders_transactions_sc_id_seq OWNED BY orders_transactions_sc.id;


--
-- Name: payment_failures; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE payment_failures (
    id bigint NOT NULL,
    checkout_id bigint NOT NULL,
    cc_exp character varying(4) DEFAULT ''::character varying NOT NULL,
    cc_name text DEFAULT ''::text NOT NULL,
    cc_street text DEFAULT ''::text NOT NULL,
    cc_postal text DEFAULT ''::text NOT NULL,
    avs_code character varying(10) DEFAULT ''::character varying NOT NULL,
    cvv2_code character varying(10) DEFAULT ''::character varying NOT NULL,
    cardholder_code character(1) NOT NULL,
    response_reason_code text DEFAULT ''::text NOT NULL,
    response_reason_text text DEFAULT ''::text NOT NULL,
    error_msg text DEFAULT ''::text NOT NULL,
    raw_data text NOT NULL,
    dt timestamp without time zone
);


ALTER TABLE payment_failures OWNER TO btqcm;

--
-- Name: payment_failures_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE payment_failures_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE payment_failures_id_seq OWNER TO btqcm;

--
-- Name: payment_failures_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE payment_failures_id_seq OWNED BY payment_failures.id;


--
-- Name: payroll; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE payroll (
    id bigint NOT NULL,
    employee_id bigint NOT NULL,
    pdate date NOT NULL,
    period_start date NOT NULL,
    period_end date,
    num_hours numeric(5,2) DEFAULT 0.00 NOT NULL,
    num_hours_reg numeric(5,2) NOT NULL,
    num_hours_ot numeric(5,2) DEFAULT 0.00 NOT NULL,
    rate_hourly numeric(8,5) DEFAULT 0.00000 NOT NULL,
    gross_hourly numeric(10,2) DEFAULT 0.00 NOT NULL,
    bonus numeric(6,2) DEFAULT 0.00 NOT NULL,
    commission numeric(6,2) DEFAULT 0.00 NOT NULL,
    healthcare_deduction numeric(6,2) DEFAULT 0.00 NOT NULL,
    taxable numeric(10,2) DEFAULT 0.00 NOT NULL,
    ss_tax numeric(6,2) DEFAULT 0.00 NOT NULL,
    med_tax numeric(6,2) DEFAULT 0.00 NOT NULL,
    fedw_tax numeric(6,2) DEFAULT 0.00 NOT NULL,
    statew_tax numeric(6,2) DEFAULT 0.00 NOT NULL,
    net numeric(10,2) DEFAULT 0.00 NOT NULL,
    bonus_breakdown text DEFAULT ''::text NOT NULL,
    commission_breakdown text DEFAULT ''::text NOT NULL,
    vacation_hours numeric(5,2) NOT NULL,
    garnishment numeric(6,2) DEFAULT 0.00 NOT NULL,
    fourzeroonek_deduction numeric(6,2) DEFAULT 0 NOT NULL,
    num_hours_juryduty numeric(5,2) DEFAULT 0.00 NOT NULL
);


ALTER TABLE payroll OWNER TO btqcm;

--
-- Name: payroll_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE payroll_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE payroll_id_seq OWNER TO btqcm;

--
-- Name: payroll_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE payroll_id_seq OWNED BY payroll.id;


--
-- Name: prices_changed_before_constraint; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE prices_changed_before_constraint (
    id bigint,
    price numeric(8,2),
    sale_price numeric(8,2),
    price_web numeric(8,2),
    sale_price_web numeric(8,2)
);


ALTER TABLE prices_changed_before_constraint OWNER TO btqcm;

--
-- Name: promotions; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE promotions (
    id bigint NOT NULL,
    title character varying(255) NOT NULL,
    code character varying(255) NOT NULL,
    discount_pct numeric(2,0) DEFAULT 0 NOT NULL,
    discount_dol numeric(4,2) DEFAULT 0.00 NOT NULL,
    free_shipping yes_no DEFAULT 'no'::yes_no NOT NULL,
    description character varying(255) NOT NULL,
    designer_ids character varying(255) DEFAULT ''::character varying NOT NULL,
    category_ids character varying(255) DEFAULT ''::character varying NOT NULL,
    grouping_ids character varying(255) DEFAULT ''::character varying NOT NULL,
    in_stock_only yes_no DEFAULT 'no'::yes_no NOT NULL,
    full_price_only yes_no DEFAULT 'no'::yes_no NOT NULL,
    on_sale_only yes_no DEFAULT 'no'::yes_no NOT NULL,
    gift_certificates yes_no DEFAULT 'no'::yes_no NOT NULL,
    minimum_items smallint DEFAULT 0 NOT NULL,
    fallback_pct numeric(2,0) DEFAULT 0 NOT NULL,
    fallback_dol numeric(4,2) DEFAULT 0.00 NOT NULL,
    date_start date NOT NULL,
    date_end date NOT NULL,
    contact_id bigint
);


ALTER TABLE promotions OWNER TO btqcm;

--
-- Name: promotions_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE promotions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE promotions_id_seq OWNER TO btqcm;

--
-- Name: promotions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE promotions_id_seq OWNED BY promotions.id;


--
-- Name: searches; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE searches (
    id bigint NOT NULL,
    term character varying(255),
    tracker_id bigint
);


ALTER TABLE searches OWNER TO btqcm;

--
-- Name: searches_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE searches_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE searches_id_seq OWNER TO btqcm;

--
-- Name: searches_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE searches_id_seq OWNED BY searches.id;


--
-- Name: shipping_methods; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE shipping_methods (
    id integer NOT NULL,
    carrier shipping_carriers NOT NULL,
    method character varying(50)
);


ALTER TABLE shipping_methods OWNER TO btqcm;

--
-- Name: shipping_methods_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE shipping_methods_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE shipping_methods_id_seq OWNER TO btqcm;

--
-- Name: shipping_methods_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE shipping_methods_id_seq OWNED BY shipping_methods.id;


--
-- Name: store_credits; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE store_credits (
    id bigint NOT NULL,
    order_id bigint NOT NULL,
    contacts_id bigint NOT NULL,
    issue_date date NOT NULL,
    notes text NOT NULL
);


ALTER TABLE store_credits OWNER TO btqcm;

--
-- Name: store_credits_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE store_credits_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE store_credits_id_seq OWNER TO btqcm;

--
-- Name: store_credits_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE store_credits_id_seq OWNED BY store_credits.id;


--
-- Name: submissions; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE submissions (
    id bigint NOT NULL,
    title text DEFAULT ''::text NOT NULL,
    image text DEFAULT ''::text NOT NULL,
    description text NOT NULL,
    link text DEFAULT ''::text NOT NULL,
    artist text DEFAULT ''::text NOT NULL,
    contact_id bigint,
    location text DEFAULT ''::text NOT NULL,
    email text DEFAULT ''::text NOT NULL,
    enter_dt timestamp without time zone DEFAULT now() NOT NULL,
    status active_or_inactive DEFAULT 'inactive'::active_or_inactive NOT NULL
);


ALTER TABLE submissions OWNER TO btqcm;

--
-- Name: submissions_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE submissions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE submissions_id_seq OWNER TO btqcm;

--
-- Name: submissions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE submissions_id_seq OWNED BY submissions.id;


--
-- Name: testimonials; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE testimonials (
    id bigint NOT NULL,
    enter_date date NOT NULL,
    text text NOT NULL,
    person character varying(50) DEFAULT ''::text NOT NULL,
    place character varying(100) DEFAULT ''::text NOT NULL,
    status active_or_inactive DEFAULT 'active'::active_or_inactive NOT NULL
);


ALTER TABLE testimonials OWNER TO btqcm;

--
-- Name: testimonials_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE testimonials_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE testimonials_id_seq OWNER TO btqcm;

--
-- Name: testimonials_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE testimonials_id_seq OWNED BY testimonials.id;


--
-- Name: tracker2; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE tracker2 (
    id bigint NOT NULL,
    visitdatetime timestamp without time zone DEFAULT now() NOT NULL,
    referer bigint NOT NULL,
    ip inet NOT NULL,
    browser bigint NOT NULL,
    web_page bigint NOT NULL,
    cookie_id text NOT NULL,
    ad_code bigint
);


ALTER TABLE tracker2 OWNER TO btqcm;

--
-- Name: tracker2_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE tracker2_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE tracker2_id_seq OWNER TO btqcm;

--
-- Name: tracker2_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE tracker2_id_seq OWNED BY tracker2.id;


--
-- Name: tracker_browsers; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE tracker_browsers (
    id bigint NOT NULL,
    browser character varying NOT NULL
);


ALTER TABLE tracker_browsers OWNER TO btqcm;

--
-- Name: tracker_referers; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE tracker_referers (
    id bigint NOT NULL,
    url character varying NOT NULL
);


ALTER TABLE tracker_referers OWNER TO btqcm;

--
-- Name: tracker_web_pages; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE tracker_web_pages (
    id bigint NOT NULL,
    url_path character varying NOT NULL
);


ALTER TABLE tracker_web_pages OWNER TO btqcm;

--
-- Name: tracker2_view; Type: VIEW; Schema: public; Owner: btqcm
--

CREATE VIEW tracker2_view AS
 SELECT t.id,
    t.visitdatetime,
    tr.url AS referer,
    t.ip,
    tb.browser,
    twp.url_path AS web_page,
    t.cookie_id,
    t.ad_code
   FROM (((tracker2 t
     LEFT JOIN tracker_referers tr ON ((t.referer = tr.id)))
     LEFT JOIN tracker_browsers tb ON ((t.browser = tb.id)))
     LEFT JOIN tracker_web_pages twp ON ((t.web_page = twp.id)));


ALTER TABLE tracker2_view OWNER TO btqcm;

--
-- Name: tracker_browsers_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE tracker_browsers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE tracker_browsers_id_seq OWNER TO btqcm;

--
-- Name: tracker_browsers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE tracker_browsers_id_seq OWNED BY tracker_browsers.id;


--
-- Name: tracker_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE tracker_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE tracker_id_seq OWNER TO btqcm;

--
-- Name: tracker_referers_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE tracker_referers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE tracker_referers_id_seq OWNER TO btqcm;

--
-- Name: tracker_referers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE tracker_referers_id_seq OWNED BY tracker_referers.id;


--
-- Name: tracker_web_pages_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE tracker_web_pages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE tracker_web_pages_id_seq OWNER TO btqcm;

--
-- Name: tracker_web_pages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE tracker_web_pages_id_seq OWNED BY tracker_web_pages.id;


--
-- Name: vendor_contacts; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE vendor_contacts (
    id bigint NOT NULL,
    contact_type vendor_contact_type DEFAULT 'designer'::vendor_contact_type NOT NULL,
    contact_title text DEFAULT ''::text NOT NULL,
    contact_address text DEFAULT ''::text NOT NULL,
    contact_address2 text DEFAULT ''::text NOT NULL,
    contact_city text DEFAULT ''::text NOT NULL,
    contact_state text DEFAULT ''::text NOT NULL,
    contact_country text DEFAULT ''::text NOT NULL,
    contact_postalcode text DEFAULT ''::text NOT NULL,
    contact_phone text DEFAULT ''::text NOT NULL,
    contact_phonecell text DEFAULT ''::text NOT NULL,
    contact_fax text DEFAULT ''::text NOT NULL,
    contact_email text DEFAULT ''::text NOT NULL,
    contact_notes text NOT NULL
);


ALTER TABLE vendor_contacts OWNER TO btqcm;

--
-- Name: vendor_contacts_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE vendor_contacts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE vendor_contacts_id_seq OWNER TO btqcm;

--
-- Name: vendor_contacts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE vendor_contacts_id_seq OWNED BY vendor_contacts.id;


--
-- Name: vendor_designers; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE vendor_designers (
    id bigint NOT NULL,
    name character varying(255) DEFAULT ''::text NOT NULL,
    shortname character varying(10) DEFAULT ''::character varying NOT NULL,
    description text NOT NULL,
    picfile character varying(255) DEFAULT ''::text NOT NULL,
    catalog_name_image character varying(100) DEFAULT ''::text NOT NULL,
    showroom_text text NOT NULL,
    showroom_instock_link yes_no DEFAULT 'no'::yes_no NOT NULL,
    showroom_specialorder_link yes_no DEFAULT 'no'::yes_no NOT NULL,
    special_order_text text NOT NULL,
    designers_page_link yes_no DEFAULT 'yes'::yes_no NOT NULL,
    status_web active_or_inactive DEFAULT 'inactive'::active_or_inactive NOT NULL,
    status_store active_or_inactive DEFAULT 'active'::active_or_inactive NOT NULL,
    status_featured designer_status_featured DEFAULT 'no'::designer_status_featured NOT NULL,
    featured_text text NOT NULL,
    ais_delay smallint DEFAULT (10)::smallint NOT NULL,
    ais_delay_text text NOT NULL,
    size_scale_ids character varying(100) DEFAULT ''::text NOT NULL,
    keywords character varying(255) DEFAULT ''::text NOT NULL,
    des_address character varying(100) DEFAULT ''::text NOT NULL,
    des_address2 character varying(100) DEFAULT ''::text NOT NULL,
    des_city character varying(50) DEFAULT ''::text NOT NULL,
    des_state character varying(50) DEFAULT ''::text NOT NULL,
    des_country character varying(50) DEFAULT ''::text NOT NULL,
    des_postalcode character varying(20) DEFAULT ''::text NOT NULL,
    des_phone character varying(20) DEFAULT ''::text NOT NULL,
    des_fax character varying(20) DEFAULT ''::text NOT NULL,
    des_email character varying(100) DEFAULT ''::text NOT NULL,
    des_ra_address character varying(100) DEFAULT ''::text NOT NULL,
    des_ra_address2 character varying(100) DEFAULT ''::text NOT NULL,
    des_ra_city character varying(50) DEFAULT ''::text NOT NULL,
    des_ra_state character varying(50) DEFAULT ''::text NOT NULL,
    des_ra_country character varying(50) DEFAULT ''::text NOT NULL,
    des_ra_postalcode character varying(20) DEFAULT ''::text NOT NULL,
    des_ra_phone character varying(20) DEFAULT ''::text NOT NULL,
    des_ra_fax character varying(20) DEFAULT ''::text NOT NULL,
    des_ra_email character varying(100) DEFAULT ''::text NOT NULL,
    des_pay_address character varying(100) DEFAULT ''::text NOT NULL,
    des_pay_address2 character varying(100) DEFAULT ''::text NOT NULL,
    des_pay_city character varying(50) DEFAULT ''::text NOT NULL,
    des_pay_state character varying(50) DEFAULT ''::text NOT NULL,
    des_pay_country character varying(50) DEFAULT ''::text NOT NULL,
    des_pay_postalcode character varying(20) DEFAULT ''::text NOT NULL,
    des_pay_phone character varying(20) DEFAULT ''::text NOT NULL,
    des_pay_fax character varying(20) DEFAULT ''::text NOT NULL,
    des_pay_email character varying(100) DEFAULT ''::text NOT NULL,
    des_account character varying(50) DEFAULT ''::text NOT NULL,
    payto designer_pay_to DEFAULT 'designer'::designer_pay_to NOT NULL,
    des_terms designer_terms DEFAULT 'Net30'::designer_terms NOT NULL,
    des_acceptcc yes_no DEFAULT 'yes'::yes_no NOT NULL,
    des_url character varying(255) DEFAULT ''::text NOT NULL,
    notes text NOT NULL
);


ALTER TABLE vendor_designers OWNER TO btqcm;

--
-- Name: vendor_designers_factors; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE vendor_designers_factors (
    id bigint NOT NULL,
    designer_id bigint NOT NULL,
    factor_id bigint NOT NULL
);


ALTER TABLE vendor_designers_factors OWNER TO btqcm;

--
-- Name: vendor_designers_factors_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE vendor_designers_factors_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE vendor_designers_factors_id_seq OWNER TO btqcm;

--
-- Name: vendor_designers_factors_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE vendor_designers_factors_id_seq OWNED BY vendor_designers_factors.id;


--
-- Name: vendor_designers_featured_images; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE vendor_designers_featured_images (
    id bigint NOT NULL,
    designer_id bigint NOT NULL,
    image_file text DEFAULT ''::text NOT NULL,
    image_order smallint DEFAULT 0 NOT NULL,
    item_id bigint
);


ALTER TABLE vendor_designers_featured_images OWNER TO btqcm;

--
-- Name: vendor_designers_featured_images_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE vendor_designers_featured_images_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE vendor_designers_featured_images_id_seq OWNER TO btqcm;

--
-- Name: vendor_designers_featured_images_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE vendor_designers_featured_images_id_seq OWNED BY vendor_designers_featured_images.id;


--
-- Name: vendor_designers_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE vendor_designers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE vendor_designers_id_seq OWNER TO btqcm;

--
-- Name: vendor_designers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE vendor_designers_id_seq OWNED BY vendor_designers.id;


--
-- Name: vendor_designers_representatives; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE vendor_designers_representatives (
    id bigint NOT NULL,
    designer_id bigint NOT NULL,
    representative_id bigint NOT NULL
);


ALTER TABLE vendor_designers_representatives OWNER TO btqcm;

--
-- Name: vendor_designers_representatives_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE vendor_designers_representatives_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE vendor_designers_representatives_id_seq OWNER TO btqcm;

--
-- Name: vendor_designers_representatives_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE vendor_designers_representatives_id_seq OWNED BY vendor_designers_representatives.id;


--
-- Name: vendor_factors; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE vendor_factors (
    id bigint NOT NULL,
    name text NOT NULL,
    fac_pay_address text DEFAULT ''::text NOT NULL,
    fac_pay_address2 text DEFAULT ''::text NOT NULL,
    fac_pay_city text DEFAULT ''::text NOT NULL,
    fac_pay_state text DEFAULT ''::text NOT NULL,
    fac_pay_country text DEFAULT ''::text NOT NULL,
    fac_pay_postalcode text DEFAULT ''::text NOT NULL,
    fac_pay_phone text DEFAULT ''::text NOT NULL,
    fac_pay_fax text DEFAULT ''::text NOT NULL,
    fac_pay_email text DEFAULT ''::text NOT NULL,
    fac_account text DEFAULT ''::text NOT NULL,
    notes text NOT NULL
);


ALTER TABLE vendor_factors OWNER TO btqcm;

--
-- Name: vendor_factors_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE vendor_factors_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE vendor_factors_id_seq OWNER TO btqcm;

--
-- Name: vendor_factors_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE vendor_factors_id_seq OWNED BY vendor_factors.id;


--
-- Name: vendor_representatives; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE vendor_representatives (
    id bigint NOT NULL,
    name text DEFAULT ''::text NOT NULL,
    rep_address text DEFAULT ''::text NOT NULL,
    rep_address2 text DEFAULT ''::text NOT NULL,
    rep_city text DEFAULT ''::text NOT NULL,
    rep_state text DEFAULT ''::text NOT NULL,
    rep_country text DEFAULT ''::text NOT NULL,
    rep_postalcode text DEFAULT ''::text NOT NULL,
    rep_phone text DEFAULT ''::text NOT NULL,
    rep_fax text DEFAULT ''::text NOT NULL,
    rep_email text DEFAULT ''::text NOT NULL,
    rep_ra_address text DEFAULT ''::text NOT NULL,
    rep_ra_address2 text DEFAULT ''::text NOT NULL,
    rep_ra_city text DEFAULT ''::text NOT NULL,
    rep_ra_state text DEFAULT ''::text NOT NULL,
    rep_ra_country text DEFAULT ''::text NOT NULL,
    rep_ra_postalcode text DEFAULT ''::text NOT NULL,
    rep_ra_phone text DEFAULT ''::text NOT NULL,
    rep_ra_fax text DEFAULT ''::text NOT NULL,
    rep_ra_email text DEFAULT ''::text NOT NULL,
    rep_pay_address text DEFAULT ''::text NOT NULL,
    rep_pay_address2 text DEFAULT ''::text NOT NULL,
    rep_pay_city text DEFAULT ''::text NOT NULL,
    rep_pay_state text DEFAULT ''::text NOT NULL,
    rep_pay_country text DEFAULT ''::text NOT NULL,
    rep_pay_postalcode text DEFAULT ''::text NOT NULL,
    rep_pay_phone text DEFAULT ''::text NOT NULL,
    rep_pay_fax text DEFAULT ''::text NOT NULL,
    rep_pay_email text DEFAULT ''::text NOT NULL,
    rep_account text DEFAULT ''::text NOT NULL,
    rep_terms vendor_rep_terms DEFAULT 'Net30'::vendor_rep_terms NOT NULL,
    rep_url text DEFAULT ''::text NOT NULL,
    notes text NOT NULL
);


ALTER TABLE vendor_representatives OWNER TO btqcm;

--
-- Name: vendor_representatives_factors; Type: TABLE; Schema: public; Owner: btqcm
--

CREATE TABLE vendor_representatives_factors (
    id bigint NOT NULL,
    representative_id bigint NOT NULL,
    factor_id bigint NOT NULL
);


ALTER TABLE vendor_representatives_factors OWNER TO btqcm;

--
-- Name: vendor_representatives_factors_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE vendor_representatives_factors_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE vendor_representatives_factors_id_seq OWNER TO btqcm;

--
-- Name: vendor_representatives_factors_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE vendor_representatives_factors_id_seq OWNED BY vendor_representatives_factors.id;


--
-- Name: vendor_representatives_id_seq; Type: SEQUENCE; Schema: public; Owner: btqcm
--

CREATE SEQUENCE vendor_representatives_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE vendor_representatives_id_seq OWNER TO btqcm;

--
-- Name: vendor_representatives_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: btqcm
--

ALTER SEQUENCE vendor_representatives_id_seq OWNED BY vendor_representatives.id;


--
-- Name: accounts id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY accounts ALTER COLUMN id SET DEFAULT nextval('accounts_id_seq'::regclass);


--
-- Name: ad_codes id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY ad_codes ALTER COLUMN id SET DEFAULT nextval('ad_codes_id_seq'::regclass);


--
-- Name: admin_roles id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY admin_roles ALTER COLUMN id SET DEFAULT nextval('admin_roles_id_seq'::regclass);


--
-- Name: admins id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY admins ALTER COLUMN id SET DEFAULT nextval('admins_id_seq'::regclass);


--
-- Name: cart_items id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY cart_items ALTER COLUMN id SET DEFAULT nextval('cart_items_id_seq'::regclass);


--
-- Name: carts id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY carts ALTER COLUMN id SET DEFAULT nextval('carts_id_seq'::regclass);


--
-- Name: checking id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY checking ALTER COLUMN id SET DEFAULT nextval('checking_id_seq'::regclass);


--
-- Name: checkout_gcs id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY checkout_gcs ALTER COLUMN id SET DEFAULT nextval('checkout_gcs_id_seq'::regclass);


--
-- Name: checkouts id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY checkouts ALTER COLUMN id SET DEFAULT nextval('checkouts_id_seq'::regclass);


--
-- Name: contact_authnet_payment_profiles id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY contact_authnet_payment_profiles ALTER COLUMN id SET DEFAULT nextval('contact_authnet_payment_profiles_id_seq'::regclass);


--
-- Name: contact_authnet_profiles id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY contact_authnet_profiles ALTER COLUMN id SET DEFAULT nextval('contact_authnet_profiles_id_seq'::regclass);


--
-- Name: contact_requests id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY contact_requests ALTER COLUMN id SET DEFAULT nextval('contact_requests_id_seq'::regclass);


--
-- Name: contacts id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY contacts ALTER COLUMN id SET DEFAULT nextval('contacts_id_seq'::regclass);


--
-- Name: contacts_ml_removes id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY contacts_ml_removes ALTER COLUMN id SET DEFAULT nextval('contacts_ml_removes_id_seq'::regclass);


--
-- Name: contacts_postcards_sends id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY contacts_postcards_sends ALTER COLUMN id SET DEFAULT nextval('contacts_postcards_sends_id_seq'::regclass);


--
-- Name: customer_appreciation id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY customer_appreciation ALTER COLUMN id SET DEFAULT nextval('customer_appreciation_id_seq'::regclass);


--
-- Name: designer_order_item_status id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY designer_order_item_status ALTER COLUMN id SET DEFAULT nextval('designer_order_item_status_id_seq'::regclass);


--
-- Name: designer_order_items id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY designer_order_items ALTER COLUMN id SET DEFAULT nextval('designer_order_items_id_seq'::regclass);


--
-- Name: designer_orders id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY designer_orders ALTER COLUMN id SET DEFAULT nextval('designer_orders_id_seq'::regclass);


--
-- Name: email_contacts id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY email_contacts ALTER COLUMN id SET DEFAULT nextval('email_contacts_id_seq'::regclass);


--
-- Name: email_images id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY email_images ALTER COLUMN id SET DEFAULT nextval('email_images_id_seq'::regclass);


--
-- Name: email_outbox id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY email_outbox ALTER COLUMN id SET DEFAULT nextval('email_outbox_id_seq'::regclass);


--
-- Name: email_queue id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY email_queue ALTER COLUMN id SET DEFAULT nextval('email_queue_id_seq'::regclass);


--
-- Name: email_send_requests id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY email_send_requests ALTER COLUMN id SET DEFAULT nextval('email_send_requests_id_seq'::regclass);


--
-- Name: emails id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY emails ALTER COLUMN id SET DEFAULT nextval('emails_id_seq'::regclass);


--
-- Name: emails_sent_xx id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY emails_sent_xx ALTER COLUMN id SET DEFAULT nextval('emails_sent_id_seq'::regclass);


--
-- Name: employees id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY employees ALTER COLUMN id SET DEFAULT nextval('employees_id_seq'::regclass);


--
-- Name: general_journal id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY general_journal ALTER COLUMN id SET DEFAULT nextval('general_journal_id_seq'::regclass);


--
-- Name: gift_certificates id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY gift_certificates ALTER COLUMN id SET DEFAULT nextval('gift_certificates_id_seq'::regclass);


--
-- Name: gtd_someday_maybe id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY gtd_someday_maybe ALTER COLUMN id SET DEFAULT nextval('gtd_someday_maybe_id_seq'::regclass);


--
-- Name: inventory_adjustments id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_adjustments ALTER COLUMN id SET DEFAULT nextval('inventory_adjustments_id_seq'::regclass);


--
-- Name: inventory_ais_reports id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_ais_reports ALTER COLUMN id SET DEFAULT nextval('inventory_ais_reports_id_seq'::regclass);


--
-- Name: inventory_categories id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_categories ALTER COLUMN id SET DEFAULT nextval('inventory_categories_id_seq'::regclass);


--
-- Name: inventory_cross_sell id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_cross_sell ALTER COLUMN id SET DEFAULT nextval('inventory_cross_sell_id_seq'::regclass);


--
-- Name: inventory_groupings id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_groupings ALTER COLUMN id SET DEFAULT nextval('inventory_groupings_id_seq'::regclass);


--
-- Name: inventory_item_breakdown id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_item_breakdown ALTER COLUMN id SET DEFAULT nextval('inventory_item_breakdown_id_seq'::regclass);


--
-- Name: inventory_item_categories id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_item_categories ALTER COLUMN id SET DEFAULT nextval('inventory_item_categories_id_seq'::regclass);


--
-- Name: inventory_item_colors id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_item_colors ALTER COLUMN id SET DEFAULT nextval('inventory_item_colors_id_seq'::regclass);


--
-- Name: inventory_item_images id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_item_images ALTER COLUMN id SET DEFAULT nextval('inventory_item_images_id_seq'::regclass);


--
-- Name: inventory_items id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_items ALTER COLUMN id SET DEFAULT nextval('inventory_items_id_seq'::regclass);


--
-- Name: inventory_items_groupings id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_items_groupings ALTER COLUMN id SET DEFAULT nextval('inventory_items_groupings_id_seq'::regclass);


--
-- Name: inventory_items_popular id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_items_popular ALTER COLUMN id SET DEFAULT nextval('inventory_items_popular_id_seq'::regclass);


--
-- Name: inventory_reports id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_reports ALTER COLUMN id SET DEFAULT nextval('inventory_reports_id_seq'::regclass);


--
-- Name: inventory_seasons id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_seasons ALTER COLUMN id SET DEFAULT nextval('inventory_seasons_id_seq'::regclass);


--
-- Name: inventory_shipment_items id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_shipment_items ALTER COLUMN id SET DEFAULT nextval('inventory_shipment_items_id_seq'::regclass);


--
-- Name: inventory_shipments id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_shipments ALTER COLUMN id SET DEFAULT nextval('inventory_shipments_id_seq'::regclass);


--
-- Name: inventory_size_scale_sizes id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_size_scale_sizes ALTER COLUMN id SET DEFAULT nextval('inventory_size_scale_sizes_id_seq'::regclass);


--
-- Name: inventory_size_scales id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_size_scales ALTER COLUMN id SET DEFAULT nextval('inventory_size_scales_id_seq'::regclass);


--
-- Name: inventory_sizes id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_sizes ALTER COLUMN id SET DEFAULT nextval('inventory_sizes_id_seq'::regclass);


--
-- Name: invoice_shipments id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY invoice_shipments ALTER COLUMN id SET DEFAULT nextval('invoice_shipments_id_seq'::regclass);


--
-- Name: invoices id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY invoices ALTER COLUMN id SET DEFAULT nextval('invoices_id_seq'::regclass);


--
-- Name: licenses id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY licenses ALTER COLUMN id SET DEFAULT nextval('licenses_id_seq'::regclass);


--
-- Name: news id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY news ALTER COLUMN id SET DEFAULT nextval('news_id_seq'::regclass);


--
-- Name: news_images id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY news_images ALTER COLUMN id SET DEFAULT nextval('news_images_id_seq'::regclass);


--
-- Name: order_item_status id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY order_item_status ALTER COLUMN id SET DEFAULT nextval('order_item_status_id_seq'::regclass);


--
-- Name: order_item_status_history id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY order_item_status_history ALTER COLUMN id SET DEFAULT nextval('order_item_status_history_id_seq'::regclass);


--
-- Name: order_items id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY order_items ALTER COLUMN id SET DEFAULT nextval('order_items_id_seq'::regclass);


--
-- Name: order_return_items id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY order_return_items ALTER COLUMN id SET DEFAULT nextval('order_return_items_id_seq'::regclass);


--
-- Name: order_returns id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY order_returns ALTER COLUMN id SET DEFAULT nextval('order_returns_id_seq'::regclass);


--
-- Name: order_shipment_items id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY order_shipment_items ALTER COLUMN id SET DEFAULT nextval('order_shipment_items_id_seq'::regclass);


--
-- Name: order_shipments id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY order_shipments ALTER COLUMN id SET DEFAULT nextval('order_shipments_id_seq'::regclass);


--
-- Name: orders id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY orders ALTER COLUMN id SET DEFAULT nextval('orders_id_seq'::regclass);


--
-- Name: orders_transactions id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY orders_transactions ALTER COLUMN id SET DEFAULT nextval('orders_transactions_id_seq'::regclass);


--
-- Name: orders_transactions_cc id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY orders_transactions_cc ALTER COLUMN id SET DEFAULT nextval('orders_transactions_cc_id_seq'::regclass);


--
-- Name: orders_transactions_deposit_items id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY orders_transactions_deposit_items ALTER COLUMN id SET DEFAULT nextval('orders_transactions_deposit_items_id_seq'::regclass);


--
-- Name: orders_transactions_gc id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY orders_transactions_gc ALTER COLUMN id SET DEFAULT nextval('orders_transactions_gc_id_seq'::regclass);


--
-- Name: orders_transactions_items id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY orders_transactions_items ALTER COLUMN id SET DEFAULT nextval('orders_transactions_items_id_seq'::regclass);


--
-- Name: orders_transactions_sc id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY orders_transactions_sc ALTER COLUMN id SET DEFAULT nextval('orders_transactions_sc_id_seq'::regclass);


--
-- Name: payment_failures id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY payment_failures ALTER COLUMN id SET DEFAULT nextval('payment_failures_id_seq'::regclass);


--
-- Name: payroll id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY payroll ALTER COLUMN id SET DEFAULT nextval('payroll_id_seq'::regclass);


--
-- Name: promotions id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY promotions ALTER COLUMN id SET DEFAULT nextval('promotions_id_seq'::regclass);


--
-- Name: searches id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY searches ALTER COLUMN id SET DEFAULT nextval('searches_id_seq'::regclass);


--
-- Name: shipping_methods id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY shipping_methods ALTER COLUMN id SET DEFAULT nextval('shipping_methods_id_seq'::regclass);


--
-- Name: store_credits id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY store_credits ALTER COLUMN id SET DEFAULT nextval('store_credits_id_seq'::regclass);


--
-- Name: submissions id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY submissions ALTER COLUMN id SET DEFAULT nextval('submissions_id_seq'::regclass);


--
-- Name: testimonials id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY testimonials ALTER COLUMN id SET DEFAULT nextval('testimonials_id_seq'::regclass);


--
-- Name: tracker2 id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY tracker2 ALTER COLUMN id SET DEFAULT nextval('tracker2_id_seq'::regclass);


--
-- Name: tracker_browsers id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY tracker_browsers ALTER COLUMN id SET DEFAULT nextval('tracker_browsers_id_seq'::regclass);


--
-- Name: tracker_referers id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY tracker_referers ALTER COLUMN id SET DEFAULT nextval('tracker_referers_id_seq'::regclass);


--
-- Name: tracker_web_pages id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY tracker_web_pages ALTER COLUMN id SET DEFAULT nextval('tracker_web_pages_id_seq'::regclass);


--
-- Name: vendor_contacts id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY vendor_contacts ALTER COLUMN id SET DEFAULT nextval('vendor_contacts_id_seq'::regclass);


--
-- Name: vendor_designers id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY vendor_designers ALTER COLUMN id SET DEFAULT nextval('vendor_designers_id_seq'::regclass);


--
-- Name: vendor_designers_factors id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY vendor_designers_factors ALTER COLUMN id SET DEFAULT nextval('vendor_designers_factors_id_seq'::regclass);


--
-- Name: vendor_designers_featured_images id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY vendor_designers_featured_images ALTER COLUMN id SET DEFAULT nextval('vendor_designers_featured_images_id_seq'::regclass);


--
-- Name: vendor_designers_representatives id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY vendor_designers_representatives ALTER COLUMN id SET DEFAULT nextval('vendor_designers_representatives_id_seq'::regclass);


--
-- Name: vendor_factors id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY vendor_factors ALTER COLUMN id SET DEFAULT nextval('vendor_factors_id_seq'::regclass);


--
-- Name: vendor_representatives id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY vendor_representatives ALTER COLUMN id SET DEFAULT nextval('vendor_representatives_id_seq'::regclass);


--
-- Name: vendor_representatives_factors id; Type: DEFAULT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY vendor_representatives_factors ALTER COLUMN id SET DEFAULT nextval('vendor_representatives_factors_id_seq'::regclass);


--
-- Name: accounts accounts_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY accounts
    ADD CONSTRAINT accounts_pkey PRIMARY KEY (id);


--
-- Name: ad_codes ad_codes_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY ad_codes
    ADD CONSTRAINT ad_codes_pkey PRIMARY KEY (id);


--
-- Name: admin_roles admin_roles_name_unique; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY admin_roles
    ADD CONSTRAINT admin_roles_name_unique UNIQUE (name);


--
-- Name: admin_roles admin_roles_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY admin_roles
    ADD CONSTRAINT admin_roles_pkey PRIMARY KEY (id);


--
-- Name: admins admins_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY admins
    ADD CONSTRAINT admins_pkey PRIMARY KEY (id);


--
-- Name: cart_items cart_items_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY cart_items
    ADD CONSTRAINT cart_items_pkey PRIMARY KEY (id);


--
-- Name: carts carts_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY carts
    ADD CONSTRAINT carts_pkey PRIMARY KEY (id);


--
-- Name: checking_bofa checking_bofa_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY checking_bofa
    ADD CONSTRAINT checking_bofa_pkey PRIMARY KEY (id);


--
-- Name: checking checking_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY checking
    ADD CONSTRAINT checking_pkey PRIMARY KEY (id);


--
-- Name: checkout_gcs checkout_gcs_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY checkout_gcs
    ADD CONSTRAINT checkout_gcs_pkey PRIMARY KEY (id);


--
-- Name: checkouts checkouts_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY checkouts
    ADD CONSTRAINT checkouts_pkey PRIMARY KEY (id);


--
-- Name: contact_authnet_payment_profiles contact_authnet_payment_profiles_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY contact_authnet_payment_profiles
    ADD CONSTRAINT contact_authnet_payment_profiles_pkey PRIMARY KEY (id);


--
-- Name: contact_authnet_profiles contact_authnet_profiles_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY contact_authnet_profiles
    ADD CONSTRAINT contact_authnet_profiles_pkey PRIMARY KEY (id);


--
-- Name: contact_requests contact_requests_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY contact_requests
    ADD CONSTRAINT contact_requests_pkey PRIMARY KEY (id);


--
-- Name: contacts_ml_removes contacts_ml_removes_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY contacts_ml_removes
    ADD CONSTRAINT contacts_ml_removes_pkey PRIMARY KEY (id);


--
-- Name: contacts contacts_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY contacts
    ADD CONSTRAINT contacts_pkey PRIMARY KEY (id);


--
-- Name: contacts_postcards_sends contacts_postcards_sends_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY contacts_postcards_sends
    ADD CONSTRAINT contacts_postcards_sends_pkey PRIMARY KEY (id);


--
-- Name: customer_appreciation customer_appreciation_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY customer_appreciation
    ADD CONSTRAINT customer_appreciation_pkey PRIMARY KEY (id);


--
-- Name: designer_order_item_status designer_order_item_status_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY designer_order_item_status
    ADD CONSTRAINT designer_order_item_status_pkey PRIMARY KEY (id);


--
-- Name: designer_order_items designer_order_items_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY designer_order_items
    ADD CONSTRAINT designer_order_items_pkey PRIMARY KEY (id);


--
-- Name: designer_orders designer_orders_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY designer_orders
    ADD CONSTRAINT designer_orders_pkey PRIMARY KEY (id);


--
-- Name: email_contacts email_contacts_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY email_contacts
    ADD CONSTRAINT email_contacts_pkey PRIMARY KEY (id);


--
-- Name: email_images email_images_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY email_images
    ADD CONSTRAINT email_images_pkey PRIMARY KEY (id);


--
-- Name: email_outbox email_outbox_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY email_outbox
    ADD CONSTRAINT email_outbox_pkey PRIMARY KEY (id);


--
-- Name: email_queue email_queue_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY email_queue
    ADD CONSTRAINT email_queue_pkey PRIMARY KEY (id);


--
-- Name: email_send_requests email_send_requests_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY email_send_requests
    ADD CONSTRAINT email_send_requests_pkey PRIMARY KEY (id);


--
-- Name: emails emails_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY emails
    ADD CONSTRAINT emails_pkey PRIMARY KEY (id);


--
-- Name: employees employees_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY employees
    ADD CONSTRAINT employees_pkey PRIMARY KEY (id);


--
-- Name: general_journal general_journal_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY general_journal
    ADD CONSTRAINT general_journal_pkey PRIMARY KEY (id);


--
-- Name: gift_certificates gift_certificates_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY gift_certificates
    ADD CONSTRAINT gift_certificates_pkey PRIMARY KEY (id);


--
-- Name: gtd_someday_maybe gtd_someday_maybe_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY gtd_someday_maybe
    ADD CONSTRAINT gtd_someday_maybe_pkey PRIMARY KEY (id);


--
-- Name: inventory_adjustments inventory_adjustments_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_adjustments
    ADD CONSTRAINT inventory_adjustments_pkey PRIMARY KEY (id);


--
-- Name: inventory_ais_reports inventory_ais_reports_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_ais_reports
    ADD CONSTRAINT inventory_ais_reports_pkey PRIMARY KEY (id);


--
-- Name: inventory_categories inventory_categories_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_categories
    ADD CONSTRAINT inventory_categories_pkey PRIMARY KEY (id);


--
-- Name: inventory_cross_sell inventory_cross_sell_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_cross_sell
    ADD CONSTRAINT inventory_cross_sell_pkey PRIMARY KEY (id);


--
-- Name: inventory_groupings inventory_groupings_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_groupings
    ADD CONSTRAINT inventory_groupings_pkey PRIMARY KEY (id);


--
-- Name: inventory_item_breakdown inventory_item_breakdown_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_item_breakdown
    ADD CONSTRAINT inventory_item_breakdown_pkey PRIMARY KEY (id);


--
-- Name: inventory_item_categories inventory_item_categories_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_item_categories
    ADD CONSTRAINT inventory_item_categories_pkey PRIMARY KEY (id);


--
-- Name: inventory_item_colors inventory_item_colors_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_item_colors
    ADD CONSTRAINT inventory_item_colors_pkey PRIMARY KEY (id);


--
-- Name: inventory_item_images inventory_item_images_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_item_images
    ADD CONSTRAINT inventory_item_images_pkey PRIMARY KEY (id);


--
-- Name: inventory_items_groupings inventory_items_groupings_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_items_groupings
    ADD CONSTRAINT inventory_items_groupings_pkey PRIMARY KEY (id);


--
-- Name: inventory_items inventory_items_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_items
    ADD CONSTRAINT inventory_items_pkey PRIMARY KEY (id);


--
-- Name: inventory_items_popular inventory_items_popular_item_id_key; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_items_popular
    ADD CONSTRAINT inventory_items_popular_item_id_key UNIQUE (item_id);


--
-- Name: inventory_items_popular inventory_items_popular_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_items_popular
    ADD CONSTRAINT inventory_items_popular_pkey PRIMARY KEY (id);


--
-- Name: inventory_reports inventory_reports_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_reports
    ADD CONSTRAINT inventory_reports_pkey PRIMARY KEY (id);


--
-- Name: inventory_seasons inventory_seasons_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_seasons
    ADD CONSTRAINT inventory_seasons_pkey PRIMARY KEY (id);


--
-- Name: inventory_shipment_items inventory_shipment_items_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_shipment_items
    ADD CONSTRAINT inventory_shipment_items_pkey PRIMARY KEY (id);


--
-- Name: inventory_shipments inventory_shipments_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_shipments
    ADD CONSTRAINT inventory_shipments_pkey PRIMARY KEY (id);


--
-- Name: inventory_size_scale_sizes inventory_size_scale_sizes_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_size_scale_sizes
    ADD CONSTRAINT inventory_size_scale_sizes_pkey PRIMARY KEY (id);


--
-- Name: inventory_size_scales inventory_size_scales_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_size_scales
    ADD CONSTRAINT inventory_size_scales_pkey PRIMARY KEY (id);


--
-- Name: inventory_sizes inventory_sizes_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_sizes
    ADD CONSTRAINT inventory_sizes_pkey PRIMARY KEY (id);


--
-- Name: inventory_sizes inventory_sizes_size_key; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_sizes
    ADD CONSTRAINT inventory_sizes_size_key UNIQUE (size);


--
-- Name: invoice_shipments invoice_shipments_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY invoice_shipments
    ADD CONSTRAINT invoice_shipments_pkey PRIMARY KEY (id);


--
-- Name: invoices invoices_invoice_number_key; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY invoices
    ADD CONSTRAINT invoices_invoice_number_key UNIQUE (invoice_number);


--
-- Name: invoices invoices_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY invoices
    ADD CONSTRAINT invoices_pkey PRIMARY KEY (id);


--
-- Name: licenses licenses_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY licenses
    ADD CONSTRAINT licenses_pkey PRIMARY KEY (id);


--
-- Name: news_images news_images_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY news_images
    ADD CONSTRAINT news_images_pkey PRIMARY KEY (id);


--
-- Name: news news_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY news
    ADD CONSTRAINT news_pkey PRIMARY KEY (id);


--
-- Name: order_item_status_history order_item_status_history_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY order_item_status_history
    ADD CONSTRAINT order_item_status_history_pkey PRIMARY KEY (id);


--
-- Name: order_item_status order_item_status_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY order_item_status
    ADD CONSTRAINT order_item_status_pkey PRIMARY KEY (id);


--
-- Name: order_items order_items_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY order_items
    ADD CONSTRAINT order_items_pkey PRIMARY KEY (id);


--
-- Name: order_return_items order_return_items_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY order_return_items
    ADD CONSTRAINT order_return_items_pkey PRIMARY KEY (id);


--
-- Name: order_returns order_returns_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY order_returns
    ADD CONSTRAINT order_returns_pkey PRIMARY KEY (id);


--
-- Name: order_shipment_items order_shipment_items_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY order_shipment_items
    ADD CONSTRAINT order_shipment_items_pkey PRIMARY KEY (id);


--
-- Name: order_shipments order_shipments_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY order_shipments
    ADD CONSTRAINT order_shipments_pkey PRIMARY KEY (id);


--
-- Name: order_shipments order_shipments_tracking_number_key; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY order_shipments
    ADD CONSTRAINT order_shipments_tracking_number_key UNIQUE (tracking_number);


--
-- Name: orders orders_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY orders
    ADD CONSTRAINT orders_pkey PRIMARY KEY (id);


--
-- Name: orders_transactions_cc orders_transactions_cc_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY orders_transactions_cc
    ADD CONSTRAINT orders_transactions_cc_pkey PRIMARY KEY (id);


--
-- Name: orders_transactions_deposit_items orders_transactions_deposit_items_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY orders_transactions_deposit_items
    ADD CONSTRAINT orders_transactions_deposit_items_pkey PRIMARY KEY (id);


--
-- Name: orders_transactions_gc orders_transactions_gc_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY orders_transactions_gc
    ADD CONSTRAINT orders_transactions_gc_pkey PRIMARY KEY (id);


--
-- Name: orders_transactions_gc orders_transactions_gc_transactions_id_gift_certificates_id_key; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY orders_transactions_gc
    ADD CONSTRAINT orders_transactions_gc_transactions_id_gift_certificates_id_key UNIQUE (transactions_id, gift_certificates_id);


--
-- Name: orders_transactions_items orders_transactions_items_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY orders_transactions_items
    ADD CONSTRAINT orders_transactions_items_pkey PRIMARY KEY (id);


--
-- Name: orders_transactions orders_transactions_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY orders_transactions
    ADD CONSTRAINT orders_transactions_pkey PRIMARY KEY (id);


--
-- Name: orders_transactions_sc orders_transactions_sc_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY orders_transactions_sc
    ADD CONSTRAINT orders_transactions_sc_pkey PRIMARY KEY (id);


--
-- Name: orders_transactions_sc orders_transactions_sc_transactions_id_store_credits_id_key; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY orders_transactions_sc
    ADD CONSTRAINT orders_transactions_sc_transactions_id_store_credits_id_key UNIQUE (transactions_id, store_credits_id);


--
-- Name: payment_failures payment_failures_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY payment_failures
    ADD CONSTRAINT payment_failures_pkey PRIMARY KEY (id);


--
-- Name: payroll payroll_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY payroll
    ADD CONSTRAINT payroll_pkey PRIMARY KEY (id);


--
-- Name: promotions promotions_code_key; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY promotions
    ADD CONSTRAINT promotions_code_key UNIQUE (code);


--
-- Name: promotions promotions_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY promotions
    ADD CONSTRAINT promotions_pkey PRIMARY KEY (id);


--
-- Name: searches searches_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY searches
    ADD CONSTRAINT searches_pkey PRIMARY KEY (id);


--
-- Name: shipping_methods shipping_methods_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY shipping_methods
    ADD CONSTRAINT shipping_methods_pkey PRIMARY KEY (id);


--
-- Name: store_credits store_credits_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY store_credits
    ADD CONSTRAINT store_credits_pkey PRIMARY KEY (id);


--
-- Name: submissions submissions_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY submissions
    ADD CONSTRAINT submissions_pkey PRIMARY KEY (id);


--
-- Name: testimonials testimonials_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY testimonials
    ADD CONSTRAINT testimonials_pkey PRIMARY KEY (id);


--
-- Name: tracker2 tracker2_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY tracker2
    ADD CONSTRAINT tracker2_pkey PRIMARY KEY (id);


--
-- Name: tracker_browsers tracker_browsers_browser_key; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY tracker_browsers
    ADD CONSTRAINT tracker_browsers_browser_key UNIQUE (browser);


--
-- Name: tracker_browsers tracker_browsers_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY tracker_browsers
    ADD CONSTRAINT tracker_browsers_pkey PRIMARY KEY (id);


--
-- Name: tracker_referers tracker_referers_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY tracker_referers
    ADD CONSTRAINT tracker_referers_pkey PRIMARY KEY (id);


--
-- Name: tracker_referers tracker_referers_url_key; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY tracker_referers
    ADD CONSTRAINT tracker_referers_url_key UNIQUE (url);


--
-- Name: tracker_web_pages tracker_web_pages_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY tracker_web_pages
    ADD CONSTRAINT tracker_web_pages_pkey PRIMARY KEY (id);


--
-- Name: tracker_web_pages tracker_web_pages_url_path_key; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY tracker_web_pages
    ADD CONSTRAINT tracker_web_pages_url_path_key UNIQUE (url_path);


--
-- Name: vendor_contacts vendor_contacts_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY vendor_contacts
    ADD CONSTRAINT vendor_contacts_pkey PRIMARY KEY (id);


--
-- Name: vendor_designers_factors vendor_designers_factors_designer_id_factor_id_key; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY vendor_designers_factors
    ADD CONSTRAINT vendor_designers_factors_designer_id_factor_id_key UNIQUE (designer_id, factor_id);


--
-- Name: vendor_designers_factors vendor_designers_factors_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY vendor_designers_factors
    ADD CONSTRAINT vendor_designers_factors_pkey PRIMARY KEY (id);


--
-- Name: vendor_designers_featured_images vendor_designers_featured_images_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY vendor_designers_featured_images
    ADD CONSTRAINT vendor_designers_featured_images_pkey PRIMARY KEY (id);


--
-- Name: vendor_designers vendor_designers_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY vendor_designers
    ADD CONSTRAINT vendor_designers_pkey PRIMARY KEY (id);


--
-- Name: vendor_designers_representatives vendor_designers_representati_designer_id_representative_id_key; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY vendor_designers_representatives
    ADD CONSTRAINT vendor_designers_representati_designer_id_representative_id_key UNIQUE (designer_id, representative_id);


--
-- Name: vendor_designers_representatives vendor_designers_representatives_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY vendor_designers_representatives
    ADD CONSTRAINT vendor_designers_representatives_pkey PRIMARY KEY (id);


--
-- Name: vendor_factors vendor_factors_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY vendor_factors
    ADD CONSTRAINT vendor_factors_pkey PRIMARY KEY (id);


--
-- Name: vendor_representatives_factors vendor_representatives_factors_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY vendor_representatives_factors
    ADD CONSTRAINT vendor_representatives_factors_pkey PRIMARY KEY (id);


--
-- Name: vendor_representatives_factors vendor_representatives_factors_representative_id_factor_id_key; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY vendor_representatives_factors
    ADD CONSTRAINT vendor_representatives_factors_representative_id_factor_id_key UNIQUE (representative_id, factor_id);


--
-- Name: vendor_representatives vendor_representatives_pkey; Type: CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY vendor_representatives
    ADD CONSTRAINT vendor_representatives_pkey PRIMARY KEY (id);


--
-- Name: accounts_account_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX accounts_account_idx ON accounts USING btree (account);


--
-- Name: accounts_account_type_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX accounts_account_type_idx ON accounts USING btree (account_type);


--
-- Name: accounts_parent_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX accounts_parent_idx ON accounts USING btree (parent);


--
-- Name: cart_items_cart_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX cart_items_cart_id_idx ON cart_items USING btree (cart_id);


--
-- Name: cart_items_item_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX cart_items_item_id_idx ON cart_items USING btree (item_id);


--
-- Name: carts_tracker_cookie_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX carts_tracker_cookie_id_idx ON carts USING btree (tracker_cookie_id);


--
-- Name: checking_account1_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX checking_account1_idx ON checking USING btree (account1);


--
-- Name: checking_account2_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX checking_account2_idx ON checking USING btree (account2);


--
-- Name: checking_account3_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX checking_account3_idx ON checking USING btree (account3);


--
-- Name: checking_account4_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX checking_account4_idx ON checking USING btree (account4);


--
-- Name: checking_account5_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX checking_account5_idx ON checking USING btree (account5);


--
-- Name: checking_amount_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX checking_amount_idx ON checking USING btree (amount);


--
-- Name: checking_bofa_account1_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX checking_bofa_account1_idx ON checking_bofa USING btree (account1);


--
-- Name: checking_bofa_account2_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX checking_bofa_account2_idx ON checking_bofa USING btree (account2);


--
-- Name: checking_bofa_account3_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX checking_bofa_account3_idx ON checking_bofa USING btree (account3);


--
-- Name: checking_bofa_account4_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX checking_bofa_account4_idx ON checking_bofa USING btree (account4);


--
-- Name: checking_bofa_account5_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX checking_bofa_account5_idx ON checking_bofa USING btree (account5);


--
-- Name: checking_bofa_amount_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX checking_bofa_amount_idx ON checking_bofa USING btree (amount);


--
-- Name: checking_bofa_descrip_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX checking_bofa_descrip_idx ON checking_bofa USING btree (descrip);


--
-- Name: checking_descrip_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX checking_descrip_idx ON checking USING btree (descrip);


--
-- Name: checkout_gcs_checkout_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX checkout_gcs_checkout_id_idx ON checkout_gcs USING btree (checkout_id);


--
-- Name: checkout_gcs_gc_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX checkout_gcs_gc_id_idx ON checkout_gcs USING btree (gc_id);


--
-- Name: checkouts_cart_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX checkouts_cart_id_idx ON checkouts USING btree (cart_id);


--
-- Name: checkouts_order_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX checkouts_order_id_idx ON checkouts USING btree (order_id);


--
-- Name: contact_authnet_payment_profiles_authnet_payment_profile_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE UNIQUE INDEX contact_authnet_payment_profiles_authnet_payment_profile_id_idx ON contact_authnet_payment_profiles USING btree (authnet_payment_profile_id);


--
-- Name: contact_authnet_profiles_authnet_customer_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE UNIQUE INDEX contact_authnet_profiles_authnet_customer_id_idx ON contact_authnet_profiles USING btree (authnet_customer_id);


--
-- Name: contact_requests_contact_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX contact_requests_contact_id_idx ON contact_requests USING btree (contact_id);


--
-- Name: contacts_email_idx1; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE UNIQUE INDEX contacts_email_idx1 ON contacts USING btree (email);


--
-- Name: contacts_email_list_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX contacts_email_list_idx ON contacts USING btree (email_list);


--
-- Name: contacts_ml_removes_contact_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX contacts_ml_removes_contact_id_idx ON contacts_ml_removes USING btree (contact_id);


--
-- Name: contacts_name_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX contacts_name_idx ON contacts USING btree (name);


--
-- Name: contacts_postcards_sends_contact_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX contacts_postcards_sends_contact_id_idx ON contacts_postcards_sends USING btree (contact_id);


--
-- Name: contacts_state_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX contacts_state_idx ON contacts USING btree (state);


--
-- Name: email_contacts_contact_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX email_contacts_contact_id_idx ON email_contacts USING btree (contact_id);


--
-- Name: email_contacts_email_id_contact_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX email_contacts_email_id_contact_id_idx ON email_contacts USING btree (email_id, contact_id);


--
-- Name: email_contacts_email_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX email_contacts_email_id_idx ON email_contacts USING btree (email_id);


--
-- Name: email_contacts_request_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX email_contacts_request_id_idx ON email_contacts USING btree (request_id);


--
-- Name: emails_ad_code_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX emails_ad_code_id_idx ON emails USING btree (ad_code_id);


--
-- Name: emails_on_email_list_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX emails_on_email_list_idx ON emails USING btree (on_email_list);


--
-- Name: emails_send_contact_type_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX emails_send_contact_type_idx ON emails USING btree (send_contact_type);


--
-- Name: emails_sent_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX emails_sent_id_idx ON emails_sent_xx USING btree (id);


--
-- Name: general_journal_account_credit_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX general_journal_account_credit_idx ON general_journal USING btree (account_credit);


--
-- Name: general_journal_account_debit_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX general_journal_account_debit_idx ON general_journal USING btree (account_debit);


--
-- Name: general_journal_ref_number_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX general_journal_ref_number_idx ON general_journal USING btree (ref_number);


--
-- Name: gift_certificates_gc_number_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX gift_certificates_gc_number_idx ON gift_certificates USING btree (gc_number);


--
-- Name: gift_certificates_order_item_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX gift_certificates_order_item_id_idx ON gift_certificates USING btree (order_item_id);


--
-- Name: gift_certificates_redemption_code_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX gift_certificates_redemption_code_idx ON gift_certificates USING btree (redemption_code);


--
-- Name: inventory_adjustments_item_breakdown_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX inventory_adjustments_item_breakdown_id_idx ON inventory_adjustments USING btree (item_breakdown_id);


--
-- Name: inventory_ais_reports_item_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX inventory_ais_reports_item_id_idx ON inventory_ais_reports USING btree (item_id);


--
-- Name: inventory_ais_reports_report_date_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX inventory_ais_reports_report_date_idx ON inventory_ais_reports USING btree (report_date);


--
-- Name: inventory_cross_sell_item_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX inventory_cross_sell_item_id_idx ON inventory_cross_sell USING btree (item_id);


--
-- Name: inventory_groupings_designer_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX inventory_groupings_designer_id_idx ON inventory_groupings USING btree (designer_id);


--
-- Name: inventory_groupings_season_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX inventory_groupings_season_id_idx ON inventory_groupings USING btree (season_id);


--
-- Name: inventory_item_breakdown_item_color_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX inventory_item_breakdown_item_color_id_idx ON inventory_item_breakdown USING btree (item_color_id);


--
-- Name: inventory_item_breakdown_item_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX inventory_item_breakdown_item_id_idx ON inventory_item_breakdown USING btree (item_id);


--
-- Name: inventory_item_breakdown_size_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX inventory_item_breakdown_size_id_idx ON inventory_item_breakdown USING btree (size_id);


--
-- Name: inventory_item_categories_category_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX inventory_item_categories_category_id_idx ON inventory_item_categories USING btree (category_id);


--
-- Name: inventory_item_categories_item_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX inventory_item_categories_item_id_idx ON inventory_item_categories USING btree (item_id);


--
-- Name: inventory_item_colors_color_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX inventory_item_colors_color_idx ON inventory_item_colors USING btree (color);


--
-- Name: inventory_item_colors_item_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX inventory_item_colors_item_id_idx ON inventory_item_colors USING btree (item_id);


--
-- Name: inventory_item_images_item_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX inventory_item_images_item_id_idx ON inventory_item_images USING btree (item_id);


--
-- Name: inventory_items_designer_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX inventory_items_designer_id_idx ON inventory_items USING btree (designer_id);


--
-- Name: inventory_items_grouping_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX inventory_items_grouping_id_idx ON inventory_items USING btree (grouping_id);


--
-- Name: inventory_items_groupings_grouping_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX inventory_items_groupings_grouping_id_idx ON inventory_items_groupings USING btree (grouping_id);


--
-- Name: inventory_items_groupings_item_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX inventory_items_groupings_item_id_idx ON inventory_items_groupings USING btree (item_id);


--
-- Name: inventory_items_season_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX inventory_items_season_id_idx ON inventory_items USING btree (season_id);


--
-- Name: inventory_items_style_number_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX inventory_items_style_number_idx ON inventory_items USING btree (style_number);


--
-- Name: inventory_search_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX inventory_search_idx ON inventory_search_view USING gin (ts_vec);


--
-- Name: inventory_shipment_items_item_breakdown_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX inventory_shipment_items_item_breakdown_id_idx ON inventory_shipment_items USING btree (item_breakdown_id);


--
-- Name: inventory_shipment_items_item_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX inventory_shipment_items_item_id_idx ON inventory_shipment_items USING btree (item_id);


--
-- Name: inventory_shipment_items_shipment_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX inventory_shipment_items_shipment_id_idx ON inventory_shipment_items USING btree (shipment_id);


--
-- Name: inventory_shipments_designer_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX inventory_shipments_designer_id_idx ON inventory_shipments USING btree (designer_id);


--
-- Name: inventory_shipments_packing_slip_number_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX inventory_shipments_packing_slip_number_idx ON inventory_shipments USING btree (packing_slip_number);


--
-- Name: inventory_size_scale_sizes_size_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX inventory_size_scale_sizes_size_id_idx ON inventory_size_scale_sizes USING btree (size_id);


--
-- Name: inventory_size_scale_sizes_size_scale_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX inventory_size_scale_sizes_size_scale_id_idx ON inventory_size_scale_sizes USING btree (size_scale_id);


--
-- Name: invoices_designer_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX invoices_designer_id_idx ON invoices USING btree (designer_id);


--
-- Name: invoices_factor_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX invoices_factor_id_idx ON invoices USING btree (factor_id);


--
-- Name: invoices_vendor_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX invoices_vendor_id_idx ON invoices USING btree (vendor_id);


--
-- Name: news_event_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX news_event_idx ON news USING btree (event);


--
-- Name: news_status_event_date_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX news_status_event_date_id_idx ON news USING btree (status, event_date, id);


--
-- Name: order_item_status_history_order_item_status_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX order_item_status_history_order_item_status_id_idx ON order_item_status_history USING btree (order_item_status_id);


--
-- Name: order_item_status_history_order_item_status_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX order_item_status_history_order_item_status_idx ON order_item_status_history USING btree (order_item_status);


--
-- Name: order_item_status_history_shipped_from_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX order_item_status_history_shipped_from_idx ON order_item_status_history USING btree (shipped_from);


--
-- Name: order_item_status_order_item_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX order_item_status_order_item_id_idx ON order_item_status USING btree (order_item_id);


--
-- Name: order_item_status_order_item_status_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX order_item_status_order_item_status_idx ON order_item_status USING btree (order_item_status);


--
-- Name: order_item_status_shipped_from_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX order_item_status_shipped_from_idx ON order_item_status USING btree (shipped_from);


--
-- Name: order_items_item_bd_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX order_items_item_bd_id_idx ON order_items USING btree (item_bd_id);


--
-- Name: order_items_order_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX order_items_order_id_idx ON order_items USING btree (order_id);


--
-- Name: order_shipments_carrier_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX order_shipments_carrier_idx ON order_shipments USING btree (carrier);


--
-- Name: orders_ad_code_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX orders_ad_code_idx ON orders USING btree (ad_code);


--
-- Name: orders_order_status_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX orders_order_status_idx ON orders USING btree (order_status);


--
-- Name: orders_promotion_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX orders_promotion_id_idx ON orders USING btree (promotion_id);


--
-- Name: orders_salesperson1_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX orders_salesperson1_idx ON orders USING btree (salesperson1);


--
-- Name: orders_salesperson2_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX orders_salesperson2_idx ON orders USING btree (salesperson2);


--
-- Name: orders_store_receipt_number_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX orders_store_receipt_number_idx ON orders USING btree (store_receipt_number);


--
-- Name: orders_transactions_cc_cc_type_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX orders_transactions_cc_cc_type_idx ON orders_transactions_cc USING btree (cc_type);


--
-- Name: orders_transactions_cc_gateway_action_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX orders_transactions_cc_gateway_action_idx ON orders_transactions_cc USING btree (gateway_action);


--
-- Name: orders_transactions_cc_gateway_ref_trans_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX orders_transactions_cc_gateway_ref_trans_id_idx ON orders_transactions_cc USING btree (gateway_ref_trans_id);


--
-- Name: orders_transactions_cc_gateway_trans_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX orders_transactions_cc_gateway_trans_id_idx ON orders_transactions_cc USING btree (gateway_trans_id);


--
-- Name: orders_transactions_cc_status_cctr_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX orders_transactions_cc_status_cctr_idx ON orders_transactions_cc USING btree (status_cctr);


--
-- Name: orders_transactions_cc_transactions_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX orders_transactions_cc_transactions_id_idx ON orders_transactions_cc USING btree (transactions_id);


--
-- Name: orders_transactions_gc_gift_certificates_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX orders_transactions_gc_gift_certificates_id_idx ON orders_transactions_gc USING btree (gift_certificates_id);


--
-- Name: orders_transactions_method_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX orders_transactions_method_idx ON orders_transactions USING btree (method);


--
-- Name: orders_transactions_order_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX orders_transactions_order_id_idx ON orders_transactions USING btree (order_id);


--
-- Name: orders_transactions_sc_store_credits_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX orders_transactions_sc_store_credits_id_idx ON orders_transactions_sc USING btree (store_credits_id);


--
-- Name: orders_transactions_status_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX orders_transactions_status_idx ON orders_transactions USING btree (status);


--
-- Name: payment_failures_checkout_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX payment_failures_checkout_id_idx ON payment_failures USING btree (checkout_id);


--
-- Name: shipping_methods_carrier_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX shipping_methods_carrier_idx ON shipping_methods USING btree (carrier);


--
-- Name: store_credits_contacts_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX store_credits_contacts_id_idx ON store_credits USING btree (contacts_id);


--
-- Name: store_credits_order_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX store_credits_order_id_idx ON store_credits USING btree (order_id);


--
-- Name: submissions_contact_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX submissions_contact_id_idx ON submissions USING btree (contact_id);


--
-- Name: tracker2_ad_code_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX tracker2_ad_code_idx ON tracker2 USING btree (ad_code);


--
-- Name: tracker2_cookie_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX tracker2_cookie_id_idx ON tracker2 USING btree (cookie_id);


--
-- Name: tracker2_ip_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX tracker2_ip_idx ON tracker2 USING btree (ip);


--
-- Name: tracker2_referer_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX tracker2_referer_idx ON tracker2 USING btree (referer);


--
-- Name: vendor_contacts_contact_type_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX vendor_contacts_contact_type_idx ON vendor_contacts USING btree (contact_type);


--
-- Name: vendor_designers_featured_images_designer_id_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX vendor_designers_featured_images_designer_id_idx ON vendor_designers_featured_images USING btree (designer_id);


--
-- Name: vendor_designers_shortname_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX vendor_designers_shortname_idx ON vendor_designers USING btree (shortname);


--
-- Name: vendor_designers_status_featured_idx; Type: INDEX; Schema: public; Owner: btqcm
--

CREATE INDEX vendor_designers_status_featured_idx ON vendor_designers USING btree (status_featured);


--
-- Name: contacts email_lower_trigger; Type: TRIGGER; Schema: public; Owner: btqcm
--

CREATE TRIGGER email_lower_trigger BEFORE INSERT OR UPDATE OF email ON contacts FOR EACH ROW EXECUTE PROCEDURE on_contacts_email_change();


--
-- Name: inventory_items update_search_view_trigger; Type: TRIGGER; Schema: public; Owner: btqcm
--

CREATE TRIGGER update_search_view_trigger AFTER INSERT OR DELETE OR UPDATE OF name, description, keywords, designer_id ON inventory_items FOR EACH STATEMENT EXECUTE PROCEDURE on_inventory_change();


--
-- Name: designer_search_aliases update_search_view_trigger; Type: TRIGGER; Schema: public; Owner: btqcm
--

CREATE TRIGGER update_search_view_trigger AFTER INSERT OR DELETE OR UPDATE OF id, alias ON designer_search_aliases FOR EACH STATEMENT EXECUTE PROCEDURE on_inventory_change();


--
-- Name: accounts accounts_parent_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY accounts
    ADD CONSTRAINT accounts_parent_fkey FOREIGN KEY (parent) REFERENCES accounts(id);


--
-- Name: admins admins_employee_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY admins
    ADD CONSTRAINT admins_employee_id_fkey FOREIGN KEY (employee_id) REFERENCES employees(id);


--
-- Name: cart_items cart_items_cart_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY cart_items
    ADD CONSTRAINT cart_items_cart_id_fkey FOREIGN KEY (cart_id) REFERENCES carts(id);


--
-- Name: cart_items cart_items_gift_certificate_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY cart_items
    ADD CONSTRAINT cart_items_gift_certificate_id_fkey FOREIGN KEY (gift_certificate_id) REFERENCES gift_certificates(id);


--
-- Name: cart_items cart_items_item_color_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY cart_items
    ADD CONSTRAINT cart_items_item_color_id_fkey FOREIGN KEY (item_color_id) REFERENCES inventory_item_colors(id);


--
-- Name: cart_items cart_items_item_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY cart_items
    ADD CONSTRAINT cart_items_item_id_fkey FOREIGN KEY (item_id) REFERENCES inventory_items(id);


--
-- Name: cart_items cart_items_size_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY cart_items
    ADD CONSTRAINT cart_items_size_id_fkey FOREIGN KEY (size_id) REFERENCES inventory_sizes(id);


--
-- Name: carts carts_contact_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY carts
    ADD CONSTRAINT carts_contact_id_fkey FOREIGN KEY (contact_id) REFERENCES contacts(id);


--
-- Name: category_search_aliases category_search_aliases_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY category_search_aliases
    ADD CONSTRAINT category_search_aliases_id_fkey FOREIGN KEY (id) REFERENCES inventory_categories(id);


--
-- Name: checking checking_account10_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY checking
    ADD CONSTRAINT checking_account10_fkey FOREIGN KEY (account10) REFERENCES accounts(id);


--
-- Name: checking checking_account1_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY checking
    ADD CONSTRAINT checking_account1_fkey FOREIGN KEY (account1) REFERENCES accounts(id);


--
-- Name: checking checking_account2_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY checking
    ADD CONSTRAINT checking_account2_fkey FOREIGN KEY (account2) REFERENCES accounts(id);


--
-- Name: checking checking_account3_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY checking
    ADD CONSTRAINT checking_account3_fkey FOREIGN KEY (account3) REFERENCES accounts(id);


--
-- Name: checking checking_account4_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY checking
    ADD CONSTRAINT checking_account4_fkey FOREIGN KEY (account4) REFERENCES accounts(id);


--
-- Name: checking checking_account5_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY checking
    ADD CONSTRAINT checking_account5_fkey FOREIGN KEY (account5) REFERENCES accounts(id);


--
-- Name: checking checking_account6_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY checking
    ADD CONSTRAINT checking_account6_fkey FOREIGN KEY (account6) REFERENCES accounts(id);


--
-- Name: checking checking_account7_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY checking
    ADD CONSTRAINT checking_account7_fkey FOREIGN KEY (account7) REFERENCES accounts(id);


--
-- Name: checking checking_account8_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY checking
    ADD CONSTRAINT checking_account8_fkey FOREIGN KEY (account8) REFERENCES accounts(id);


--
-- Name: checking checking_account9_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY checking
    ADD CONSTRAINT checking_account9_fkey FOREIGN KEY (account9) REFERENCES accounts(id);


--
-- Name: checking_bofa checking_bofa_account10_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY checking_bofa
    ADD CONSTRAINT checking_bofa_account10_fkey FOREIGN KEY (account10) REFERENCES accounts(id);


--
-- Name: checking_bofa checking_bofa_account1_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY checking_bofa
    ADD CONSTRAINT checking_bofa_account1_fkey FOREIGN KEY (account1) REFERENCES accounts(id);


--
-- Name: checking_bofa checking_bofa_account2_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY checking_bofa
    ADD CONSTRAINT checking_bofa_account2_fkey FOREIGN KEY (account2) REFERENCES accounts(id);


--
-- Name: checking_bofa checking_bofa_account3_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY checking_bofa
    ADD CONSTRAINT checking_bofa_account3_fkey FOREIGN KEY (account3) REFERENCES accounts(id);


--
-- Name: checking_bofa checking_bofa_account4_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY checking_bofa
    ADD CONSTRAINT checking_bofa_account4_fkey FOREIGN KEY (account4) REFERENCES accounts(id);


--
-- Name: checking_bofa checking_bofa_account5_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY checking_bofa
    ADD CONSTRAINT checking_bofa_account5_fkey FOREIGN KEY (account5) REFERENCES accounts(id);


--
-- Name: checking_bofa checking_bofa_account6_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY checking_bofa
    ADD CONSTRAINT checking_bofa_account6_fkey FOREIGN KEY (account6) REFERENCES accounts(id);


--
-- Name: checking_bofa checking_bofa_account7_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY checking_bofa
    ADD CONSTRAINT checking_bofa_account7_fkey FOREIGN KEY (account7) REFERENCES accounts(id);


--
-- Name: checking_bofa checking_bofa_account8_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY checking_bofa
    ADD CONSTRAINT checking_bofa_account8_fkey FOREIGN KEY (account8) REFERENCES accounts(id);


--
-- Name: checking_bofa checking_bofa_account9_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY checking_bofa
    ADD CONSTRAINT checking_bofa_account9_fkey FOREIGN KEY (account9) REFERENCES accounts(id);


--
-- Name: checkout_gcs checkout_gcs_checkout_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY checkout_gcs
    ADD CONSTRAINT checkout_gcs_checkout_id_fkey FOREIGN KEY (checkout_id) REFERENCES checkouts(id);


--
-- Name: checkout_gcs checkout_gcs_gc_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY checkout_gcs
    ADD CONSTRAINT checkout_gcs_gc_id_fkey FOREIGN KEY (gc_id) REFERENCES gift_certificates(id);


--
-- Name: checkouts checkouts_cart_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY checkouts
    ADD CONSTRAINT checkouts_cart_id_fkey FOREIGN KEY (cart_id) REFERENCES carts(id);


--
-- Name: checkouts checkouts_contact_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY checkouts
    ADD CONSTRAINT checkouts_contact_id_fkey FOREIGN KEY (contact_id) REFERENCES contacts(id);


--
-- Name: checkouts checkouts_order_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY checkouts
    ADD CONSTRAINT checkouts_order_id_fkey FOREIGN KEY (order_id) REFERENCES orders(id);


--
-- Name: checkouts checkouts_promotion_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY checkouts
    ADD CONSTRAINT checkouts_promotion_id_fkey FOREIGN KEY (promotion_id) REFERENCES promotions(id);


--
-- Name: contact_authnet_payment_profiles contact_authnet_payment_profile_contact_authnet_profile_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY contact_authnet_payment_profiles
    ADD CONSTRAINT contact_authnet_payment_profile_contact_authnet_profile_id_fkey FOREIGN KEY (contact_authnet_profile_id) REFERENCES contact_authnet_profiles(id);


--
-- Name: contact_authnet_profiles contact_authnet_profiles_contact_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY contact_authnet_profiles
    ADD CONSTRAINT contact_authnet_profiles_contact_id_fkey FOREIGN KEY (contact_id) REFERENCES contacts(id);


--
-- Name: contact_requests contact_requests_contact_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY contact_requests
    ADD CONSTRAINT contact_requests_contact_id_fkey FOREIGN KEY (contact_id) REFERENCES contacts(id);


--
-- Name: promotions contacts_id; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY promotions
    ADD CONSTRAINT contacts_id FOREIGN KEY (contact_id) REFERENCES contacts(id);


--
-- Name: contacts_ml_removes contacts_ml_removes_contact_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY contacts_ml_removes
    ADD CONSTRAINT contacts_ml_removes_contact_id_fkey FOREIGN KEY (contact_id) REFERENCES contacts(id);


--
-- Name: customer_appreciation customer_appreciation_contact_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY customer_appreciation
    ADD CONSTRAINT customer_appreciation_contact_id_fkey FOREIGN KEY (contact_id) REFERENCES contacts(id);


--
-- Name: customer_appreciation customer_appreciation_season_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY customer_appreciation
    ADD CONSTRAINT customer_appreciation_season_id_fkey FOREIGN KEY (season_id) REFERENCES inventory_seasons(id);


--
-- Name: designer_order_item_status designer_order_item_status_order_item_status_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY designer_order_item_status
    ADD CONSTRAINT designer_order_item_status_order_item_status_id_fkey FOREIGN KEY (order_item_status_id) REFERENCES order_item_status(id);


--
-- Name: designer_order_item_status designer_order_item_status_received_shipment_item_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY designer_order_item_status
    ADD CONSTRAINT designer_order_item_status_received_shipment_item_id_fkey FOREIGN KEY (received_shipment_item_id) REFERENCES inventory_shipment_items(id);


--
-- Name: designer_order_items designer_order_items_item_breakdown_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY designer_order_items
    ADD CONSTRAINT designer_order_items_item_breakdown_id_fkey FOREIGN KEY (item_breakdown_id) REFERENCES inventory_item_breakdown(id);


--
-- Name: designer_orders designer_orders_approved_by_employee_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY designer_orders
    ADD CONSTRAINT designer_orders_approved_by_employee_id_fkey FOREIGN KEY (approved_by_employee_id) REFERENCES employees(id);


--
-- Name: designer_orders designer_orders_cancelled_by_employee_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY designer_orders
    ADD CONSTRAINT designer_orders_cancelled_by_employee_id_fkey FOREIGN KEY (cxl_by_employee_id) REFERENCES employees(id);


--
-- Name: designer_orders designer_orders_confirmation_received_by_employee_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY designer_orders
    ADD CONSTRAINT designer_orders_confirmation_received_by_employee_id_fkey FOREIGN KEY (confirmation_received_by_employee_id) REFERENCES employees(id);


--
-- Name: designer_orders designer_orders_designer_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY designer_orders
    ADD CONSTRAINT designer_orders_designer_id_fkey FOREIGN KEY (designer_id) REFERENCES vendor_designers(id);


--
-- Name: designer_orders designer_orders_entered_by_employee_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY designer_orders
    ADD CONSTRAINT designer_orders_entered_by_employee_id_fkey FOREIGN KEY (entered_by_employee_id) REFERENCES employees(id);


--
-- Name: designer_orders designer_orders_placed_by_employee_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY designer_orders
    ADD CONSTRAINT designer_orders_placed_by_employee_id_fkey FOREIGN KEY (placed_by_employee_id) REFERENCES employees(id);


--
-- Name: designer_search_aliases designer_search_aliases_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY designer_search_aliases
    ADD CONSTRAINT designer_search_aliases_id_fkey FOREIGN KEY (id) REFERENCES vendor_designers(id);


--
-- Name: designer_order_items dori_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY designer_order_items
    ADD CONSTRAINT dori_fkey FOREIGN KEY (designer_order_id) REFERENCES designer_orders(id) ON DELETE CASCADE;


--
-- Name: designer_order_item_status doris_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY designer_order_item_status
    ADD CONSTRAINT doris_fkey FOREIGN KEY (designer_order_item_id) REFERENCES designer_order_items(id) ON DELETE CASCADE;


--
-- Name: email_contacts email_contacts_contact_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY email_contacts
    ADD CONSTRAINT email_contacts_contact_id_fkey FOREIGN KEY (contact_id) REFERENCES contacts(id);


--
-- Name: email_contacts email_contacts_email_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY email_contacts
    ADD CONSTRAINT email_contacts_email_id_fkey FOREIGN KEY (email_id) REFERENCES emails(id);


--
-- Name: email_queue email_queue_contact_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY email_queue
    ADD CONSTRAINT email_queue_contact_id_fkey FOREIGN KEY (contact_id) REFERENCES contacts(id);


--
-- Name: email_queue email_queue_email_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY email_queue
    ADD CONSTRAINT email_queue_email_id_fkey FOREIGN KEY (email_id) REFERENCES emails(id);


--
-- Name: emails emails_ad_code_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY emails
    ADD CONSTRAINT emails_ad_code_id_fkey FOREIGN KEY (ad_code_id) REFERENCES ad_codes(id);


--
-- Name: emails_sent_xx emails_sent_email_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY emails_sent_xx
    ADD CONSTRAINT emails_sent_email_id_fkey FOREIGN KEY (email_id) REFERENCES emails(id);


--
-- Name: emails_sent_xx emails_sent_request_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY emails_sent_xx
    ADD CONSTRAINT emails_sent_request_id_fkey FOREIGN KEY (request_id) REFERENCES email_send_requests(id);


--
-- Name: general_journal general_journal_account_credit_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY general_journal
    ADD CONSTRAINT general_journal_account_credit_fkey FOREIGN KEY (account_credit) REFERENCES accounts(id);


--
-- Name: general_journal general_journal_account_debit_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY general_journal
    ADD CONSTRAINT general_journal_account_debit_fkey FOREIGN KEY (account_debit) REFERENCES accounts(id);


--
-- Name: gift_certificates gift_certificates_order_item_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY gift_certificates
    ADD CONSTRAINT gift_certificates_order_item_id_fkey FOREIGN KEY (order_item_id) REFERENCES order_items(id);


--
-- Name: inventory_adjustments inventory_adjustments_item_breakdown_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_adjustments
    ADD CONSTRAINT inventory_adjustments_item_breakdown_id_fkey FOREIGN KEY (item_breakdown_id) REFERENCES inventory_item_breakdown(id);


--
-- Name: inventory_ais_reports inventory_ais_reports_item_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_ais_reports
    ADD CONSTRAINT inventory_ais_reports_item_id_fkey FOREIGN KEY (item_id) REFERENCES inventory_items(id);


--
-- Name: inventory_cross_sell inventory_cross_sell_cross_item_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_cross_sell
    ADD CONSTRAINT inventory_cross_sell_cross_item_id_fkey FOREIGN KEY (cross_item_id) REFERENCES inventory_items(id);


--
-- Name: inventory_cross_sell inventory_cross_sell_item_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_cross_sell
    ADD CONSTRAINT inventory_cross_sell_item_id_fkey FOREIGN KEY (item_id) REFERENCES inventory_items(id);


--
-- Name: inventory_groupings inventory_groupings_designer_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_groupings
    ADD CONSTRAINT inventory_groupings_designer_id_fkey FOREIGN KEY (designer_id) REFERENCES vendor_designers(id);


--
-- Name: inventory_groupings inventory_groupings_season_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_groupings
    ADD CONSTRAINT inventory_groupings_season_id_fkey FOREIGN KEY (season_id) REFERENCES inventory_seasons(id);


--
-- Name: inventory_item_breakdown inventory_item_breakdown_item_color_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_item_breakdown
    ADD CONSTRAINT inventory_item_breakdown_item_color_id_fkey FOREIGN KEY (item_color_id) REFERENCES inventory_item_colors(id);


--
-- Name: inventory_item_breakdown inventory_item_breakdown_item_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_item_breakdown
    ADD CONSTRAINT inventory_item_breakdown_item_id_fkey FOREIGN KEY (item_id) REFERENCES inventory_items(id);


--
-- Name: inventory_item_breakdown inventory_item_breakdown_size_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_item_breakdown
    ADD CONSTRAINT inventory_item_breakdown_size_id_fkey FOREIGN KEY (size_id) REFERENCES inventory_sizes(id);


--
-- Name: inventory_item_categories inventory_item_categories_category_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_item_categories
    ADD CONSTRAINT inventory_item_categories_category_id_fkey FOREIGN KEY (category_id) REFERENCES inventory_categories(id);


--
-- Name: inventory_item_categories inventory_item_categories_item_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_item_categories
    ADD CONSTRAINT inventory_item_categories_item_id_fkey FOREIGN KEY (item_id) REFERENCES inventory_items(id);


--
-- Name: inventory_item_colors inventory_item_colors_item_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_item_colors
    ADD CONSTRAINT inventory_item_colors_item_id_fkey FOREIGN KEY (item_id) REFERENCES inventory_items(id);


--
-- Name: inventory_item_images inventory_item_images_item_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_item_images
    ADD CONSTRAINT inventory_item_images_item_id_fkey FOREIGN KEY (item_id) REFERENCES inventory_items(id);


--
-- Name: inventory_items inventory_items_designer_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_items
    ADD CONSTRAINT inventory_items_designer_id_fkey FOREIGN KEY (designer_id) REFERENCES vendor_designers(id);


--
-- Name: inventory_items inventory_items_grouping_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_items
    ADD CONSTRAINT inventory_items_grouping_id_fkey FOREIGN KEY (grouping_id) REFERENCES inventory_groupings(id);


--
-- Name: inventory_items_groupings inventory_items_groupings_grouping_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_items_groupings
    ADD CONSTRAINT inventory_items_groupings_grouping_id_fkey FOREIGN KEY (grouping_id) REFERENCES inventory_groupings(id);


--
-- Name: inventory_items_groupings inventory_items_groupings_item_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_items_groupings
    ADD CONSTRAINT inventory_items_groupings_item_id_fkey FOREIGN KEY (item_id) REFERENCES inventory_items(id);


--
-- Name: inventory_items_popular inventory_items_popular_item_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_items_popular
    ADD CONSTRAINT inventory_items_popular_item_id_fkey FOREIGN KEY (item_id) REFERENCES inventory_items(id);


--
-- Name: inventory_items inventory_items_season_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_items
    ADD CONSTRAINT inventory_items_season_id_fkey FOREIGN KEY (season_id) REFERENCES inventory_seasons(id);


--
-- Name: inventory_items inventory_items_size_scale_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_items
    ADD CONSTRAINT inventory_items_size_scale_id_fkey FOREIGN KEY (size_scale_id) REFERENCES inventory_size_scales(id);


--
-- Name: inventory_shipment_items inventory_shipment_items_item_breakdown_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_shipment_items
    ADD CONSTRAINT inventory_shipment_items_item_breakdown_id_fkey FOREIGN KEY (item_breakdown_id) REFERENCES inventory_item_breakdown(id);


--
-- Name: inventory_shipment_items inventory_shipment_items_item_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_shipment_items
    ADD CONSTRAINT inventory_shipment_items_item_id_fkey FOREIGN KEY (item_id) REFERENCES inventory_items(id);


--
-- Name: inventory_shipment_items inventory_shipment_items_shipment_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_shipment_items
    ADD CONSTRAINT inventory_shipment_items_shipment_id_fkey FOREIGN KEY (shipment_id) REFERENCES inventory_shipments(id);


--
-- Name: inventory_shipments inventory_shipments_designer_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_shipments
    ADD CONSTRAINT inventory_shipments_designer_id_fkey FOREIGN KEY (designer_id) REFERENCES vendor_designers(id);


--
-- Name: inventory_size_scale_sizes inventory_size_scale_sizes_size_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_size_scale_sizes
    ADD CONSTRAINT inventory_size_scale_sizes_size_id_fkey FOREIGN KEY (size_id) REFERENCES inventory_sizes(id);


--
-- Name: inventory_size_scale_sizes inventory_size_scale_sizes_size_scale_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY inventory_size_scale_sizes
    ADD CONSTRAINT inventory_size_scale_sizes_size_scale_id_fkey FOREIGN KEY (size_scale_id) REFERENCES inventory_size_scales(id);


--
-- Name: invoice_shipments invoice_shipments_invoice_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY invoice_shipments
    ADD CONSTRAINT invoice_shipments_invoice_id_fkey FOREIGN KEY (invoice_id) REFERENCES invoices(id);


--
-- Name: invoice_shipments invoice_shipments_shipment_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY invoice_shipments
    ADD CONSTRAINT invoice_shipments_shipment_id_fkey FOREIGN KEY (shipment_id) REFERENCES inventory_shipments(id);


--
-- Name: invoices invoices_designer_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY invoices
    ADD CONSTRAINT invoices_designer_id_fkey FOREIGN KEY (designer_id) REFERENCES vendor_designers(id);


--
-- Name: invoices invoices_factor_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY invoices
    ADD CONSTRAINT invoices_factor_id_fkey FOREIGN KEY (factor_id) REFERENCES vendor_factors(id);


--
-- Name: invoices invoices_vendor_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY invoices
    ADD CONSTRAINT invoices_vendor_id_fkey FOREIGN KEY (vendor_id) REFERENCES vendor_contacts(id);


--
-- Name: order_item_status_history order_item_status_history_order_item_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY order_item_status_history
    ADD CONSTRAINT order_item_status_history_order_item_id_fkey FOREIGN KEY (order_item_id) REFERENCES order_items(id);


--
-- Name: order_item_status_history order_item_status_history_order_item_status_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY order_item_status_history
    ADD CONSTRAINT order_item_status_history_order_item_status_id_fkey FOREIGN KEY (order_item_status_id) REFERENCES order_item_status(id);


--
-- Name: order_item_status order_item_status_order_item_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY order_item_status
    ADD CONSTRAINT order_item_status_order_item_id_fkey FOREIGN KEY (order_item_id) REFERENCES order_items(id);


--
-- Name: order_items order_items_item_bd_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY order_items
    ADD CONSTRAINT order_items_item_bd_id_fkey FOREIGN KEY (item_bd_id) REFERENCES inventory_item_breakdown(id);


--
-- Name: order_items order_items_item_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY order_items
    ADD CONSTRAINT order_items_item_id_fkey FOREIGN KEY (item_id) REFERENCES inventory_items(id);


--
-- Name: order_items order_items_order_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY order_items
    ADD CONSTRAINT order_items_order_id_fkey FOREIGN KEY (order_id) REFERENCES orders(id);


--
-- Name: order_return_items order_return_items_order_item_status_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY order_return_items
    ADD CONSTRAINT order_return_items_order_item_status_id_fkey FOREIGN KEY (order_item_status_id) REFERENCES order_item_status(id);


--
-- Name: order_return_items order_return_items_order_return_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY order_return_items
    ADD CONSTRAINT order_return_items_order_return_id_fkey FOREIGN KEY (order_return_id) REFERENCES order_returns(id);


--
-- Name: order_returns order_returns_order_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY order_returns
    ADD CONSTRAINT order_returns_order_id_fkey FOREIGN KEY (order_id) REFERENCES orders(id);


--
-- Name: order_returns order_returns_received_by_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY order_returns
    ADD CONSTRAINT order_returns_received_by_fkey FOREIGN KEY (received_by) REFERENCES employees(id);


--
-- Name: order_shipment_items order_shipment_items_order_item_status_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY order_shipment_items
    ADD CONSTRAINT order_shipment_items_order_item_status_id_fkey FOREIGN KEY (order_item_status_id) REFERENCES order_item_status(id);


--
-- Name: order_shipment_items order_shipment_items_order_shipment_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY order_shipment_items
    ADD CONSTRAINT order_shipment_items_order_shipment_id_fkey FOREIGN KEY (order_shipment_id) REFERENCES order_shipments(id);


--
-- Name: order_shipments order_shipments_by_employee_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY order_shipments
    ADD CONSTRAINT order_shipments_by_employee_id_fkey FOREIGN KEY (by_employee_id) REFERENCES employees(id);


--
-- Name: order_shipments order_shipments_order_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY order_shipments
    ADD CONSTRAINT order_shipments_order_id_fkey FOREIGN KEY (order_id) REFERENCES orders(id);


--
-- Name: order_shipments order_shipments_shipping_method_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY order_shipments
    ADD CONSTRAINT order_shipments_shipping_method_id_fkey FOREIGN KEY (shipping_method_id) REFERENCES shipping_methods(id);


--
-- Name: orders orders_ad_code_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY orders
    ADD CONSTRAINT orders_ad_code_fkey FOREIGN KEY (ad_code) REFERENCES ad_codes(id);


--
-- Name: orders orders_contact_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY orders
    ADD CONSTRAINT orders_contact_id_fkey FOREIGN KEY (contact_id) REFERENCES contacts(id);


--
-- Name: orders orders_promotion_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY orders
    ADD CONSTRAINT orders_promotion_id_fkey FOREIGN KEY (promotion_id) REFERENCES promotions(id);


--
-- Name: orders_transactions_cc orders_transactions_cc_contact_authnet_payment_profile_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY orders_transactions_cc
    ADD CONSTRAINT orders_transactions_cc_contact_authnet_payment_profile_id_fkey FOREIGN KEY (contact_authnet_payment_profile_id) REFERENCES contact_authnet_payment_profiles(id);


--
-- Name: orders_transactions_cc orders_transactions_cc_transactions_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY orders_transactions_cc
    ADD CONSTRAINT orders_transactions_cc_transactions_id_fkey FOREIGN KEY (transactions_id) REFERENCES orders_transactions(id);


--
-- Name: orders_transactions_deposit_items orders_transactions_deposit_items_order_item_status_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY orders_transactions_deposit_items
    ADD CONSTRAINT orders_transactions_deposit_items_order_item_status_id_fkey FOREIGN KEY (order_item_status_id) REFERENCES order_item_status(id);


--
-- Name: orders_transactions_deposit_items orders_transactions_deposit_items_transactions_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY orders_transactions_deposit_items
    ADD CONSTRAINT orders_transactions_deposit_items_transactions_id_fkey FOREIGN KEY (transactions_id) REFERENCES orders_transactions(id);


--
-- Name: orders_transactions_gc orders_transactions_gc_gift_certificates_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY orders_transactions_gc
    ADD CONSTRAINT orders_transactions_gc_gift_certificates_id_fkey FOREIGN KEY (gift_certificates_id) REFERENCES gift_certificates(id);


--
-- Name: orders_transactions_gc orders_transactions_gc_transactions_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY orders_transactions_gc
    ADD CONSTRAINT orders_transactions_gc_transactions_id_fkey FOREIGN KEY (transactions_id) REFERENCES orders_transactions(id);


--
-- Name: orders_transactions_items orders_transactions_items_order_item_status_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY orders_transactions_items
    ADD CONSTRAINT orders_transactions_items_order_item_status_id_fkey FOREIGN KEY (order_item_status_id) REFERENCES order_item_status(id);


--
-- Name: orders_transactions_items orders_transactions_items_transactions_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY orders_transactions_items
    ADD CONSTRAINT orders_transactions_items_transactions_id_fkey FOREIGN KEY (transactions_id) REFERENCES orders_transactions(id);


--
-- Name: orders_transactions orders_transactions_order_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY orders_transactions
    ADD CONSTRAINT orders_transactions_order_id_fkey FOREIGN KEY (order_id) REFERENCES orders(id);


--
-- Name: orders_transactions_sc orders_transactions_sc_store_credits_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY orders_transactions_sc
    ADD CONSTRAINT orders_transactions_sc_store_credits_id_fkey FOREIGN KEY (store_credits_id) REFERENCES store_credits(id);


--
-- Name: orders_transactions_sc orders_transactions_sc_transactions_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY orders_transactions_sc
    ADD CONSTRAINT orders_transactions_sc_transactions_id_fkey FOREIGN KEY (transactions_id) REFERENCES orders_transactions(id);


--
-- Name: payment_failures payment_failures_checkout_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY payment_failures
    ADD CONSTRAINT payment_failures_checkout_id_fkey FOREIGN KEY (checkout_id) REFERENCES checkouts(id);


--
-- Name: payroll payroll_employee_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY payroll
    ADD CONSTRAINT payroll_employee_id_fkey FOREIGN KEY (employee_id) REFERENCES employees(id);


--
-- Name: store_credits store_credits_contacts_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY store_credits
    ADD CONSTRAINT store_credits_contacts_id_fkey FOREIGN KEY (contacts_id) REFERENCES contacts(id);


--
-- Name: store_credits store_credits_order_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY store_credits
    ADD CONSTRAINT store_credits_order_id_fkey FOREIGN KEY (order_id) REFERENCES orders(id);


--
-- Name: store_credits store_credits_order_id_fkey1; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY store_credits
    ADD CONSTRAINT store_credits_order_id_fkey1 FOREIGN KEY (order_id) REFERENCES orders(id);


--
-- Name: submissions submissions_contact_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY submissions
    ADD CONSTRAINT submissions_contact_id_fkey FOREIGN KEY (contact_id) REFERENCES contacts(id);


--
-- Name: tracker2 tracker2_browser_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY tracker2
    ADD CONSTRAINT tracker2_browser_fkey FOREIGN KEY (browser) REFERENCES tracker_browsers(id);


--
-- Name: tracker2 tracker2_referer_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY tracker2
    ADD CONSTRAINT tracker2_referer_fkey FOREIGN KEY (referer) REFERENCES tracker_referers(id);


--
-- Name: tracker2 tracker2_web_page_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY tracker2
    ADD CONSTRAINT tracker2_web_page_fkey FOREIGN KEY (web_page) REFERENCES tracker_web_pages(id);


--
-- Name: vendor_designers_factors vendor_designers_factors_designer_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY vendor_designers_factors
    ADD CONSTRAINT vendor_designers_factors_designer_id_fkey FOREIGN KEY (designer_id) REFERENCES vendor_designers(id);


--
-- Name: vendor_designers_factors vendor_designers_factors_factor_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY vendor_designers_factors
    ADD CONSTRAINT vendor_designers_factors_factor_id_fkey FOREIGN KEY (factor_id) REFERENCES vendor_factors(id);


--
-- Name: vendor_designers_featured_images vendor_designers_featured_images_designer_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY vendor_designers_featured_images
    ADD CONSTRAINT vendor_designers_featured_images_designer_id_fkey FOREIGN KEY (designer_id) REFERENCES vendor_designers(id);


--
-- Name: vendor_designers_featured_images vendor_designers_featured_images_item_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY vendor_designers_featured_images
    ADD CONSTRAINT vendor_designers_featured_images_item_id_fkey FOREIGN KEY (item_id) REFERENCES inventory_items(id);


--
-- Name: vendor_designers_representatives vendor_designers_representatives_designer_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY vendor_designers_representatives
    ADD CONSTRAINT vendor_designers_representatives_designer_id_fkey FOREIGN KEY (designer_id) REFERENCES vendor_designers(id);


--
-- Name: vendor_designers_representatives vendor_designers_representatives_representative_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY vendor_designers_representatives
    ADD CONSTRAINT vendor_designers_representatives_representative_id_fkey FOREIGN KEY (representative_id) REFERENCES vendor_representatives(id);


--
-- Name: vendor_representatives_factors vendor_representatives_factors_factor_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY vendor_representatives_factors
    ADD CONSTRAINT vendor_representatives_factors_factor_id_fkey FOREIGN KEY (factor_id) REFERENCES vendor_factors(id);


--
-- Name: vendor_representatives_factors vendor_representatives_factors_representative_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: btqcm
--

ALTER TABLE ONLY vendor_representatives_factors
    ADD CONSTRAINT vendor_representatives_factors_representative_id_fkey FOREIGN KEY (representative_id) REFERENCES vendor_representatives(id);


--
-- PostgreSQL database dump complete
--

