

CREATE TABLE cambios (
    timestamp_ text,
    nombre_disparador text,
    tipo_disparador text,
    nivel_disparador text,
    comando text NOT NULL,
    id integer NOT NULL
);

--
-- TOC entry 166 (class 1259 OID 17387)
-- Dependencies: 161 5
-- Name: cambios_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

--CREATE SEQUENCE cambios_id_seq
--    START WITH 1
--    INCREMENT BY 1
--    NO MINVALUE
--    NO MAXVALUE
--    CACHE 1;


--
-- TOC entry 163 (class 1259 OID 17354)
-- Dependencies: 1771 5
-- Name: person; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE person (
    id integer NOT NULL,
    name text,
    age integer,
    serverid character varying NOT NULL,
    --serverid character varying DEFAULT 'server1'::character varying NOT NULL,
    server integer
);

--
-- TOC entry 162 (class 1259 OID 17352)
-- Dependencies: 163 5
-- Name: person_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

--CREATE SEQUENCE person_id_seq
--    START WITH 1
--    INCREMENT BY 1
--    NO MINVALUE
--    NO MAXVALUE
 --   CACHE 1;


--
-- TOC entry 165 (class 1259 OID 17370)
-- Dependencies: 5
-- Name: server; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE server (
    id integer NOT NULL,
    name text
);


----ALTER TABLE public.server OWNER TO postgres;

--
-- TOC entry 164 (class 1259 OID 17368)
-- Dependencies: 5 165
-- Name: server_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

--CREATE SEQUENCE server_id_seq
--    START WITH 1
--    INCREMENT BY 1
--    NO MINVALUE
--    NO MAXVALUE
--    CACHE 1;


----ALTER TABLE public.server_id_seq OWNER TO postgres;

--
-- TOC entry 1900 (class 0 OID 0)
-- Dependencies: 164
-- Name: server_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

--ALTER SEQUENCE server_id_seq OWNED BY server.id;


--
-- TOC entry 1769 (class 2604 OID 17389)
-- Dependencies: 166 161
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

--ALTER TABLE ONLY cambios ALTER COLUMN id SET DEFAULT nextval('cambios_id_seq'::regclass);


--
-- TOC entry 1770 (class 2604 OID 17357)
-- Dependencies: 162 163 163
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

--ALTER TABLE ONLY person ALTER COLUMN id SET DEFAULT nextval('person_id_seq'::regclass);


--
-- TOC entry 1772 (class 2604 OID 17373)
-- Dependencies: 165 164 165
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

--ALTER TABLE ONLY server ALTER COLUMN id SET DEFAULT nextval('server_id_seq'::regclass);


--
-- TOC entry 1883 (class 0 OID 17345)
-- Dependencies: 161 1889
-- Data for Name: cambios; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO cambios VALUES ('2015-07-09 14:26:41.234752-04', 'grabar_operaciones', 'AFTER', 'STATEMENT', 'INSERT', 1);
INSERT INTO cambios VALUES ('2015-07-09 14:26:47.738623-04', 'grabar_operaciones', 'AFTER', 'STATEMENT', 'INSERT', 2);
INSERT INTO cambios VALUES ('2015-07-09 14:28:24.985777-04', 'grabar_operaciones', 'AFTER', 'STATEMENT', 'UPDATE', 3);
INSERT INTO cambios VALUES ('2015-07-09 14:50:43.312299-04', 'grabar_operaciones', 'AFTER', 'STATEMENT', 'UPDATE', 4);
INSERT INTO cambios VALUES ('2015-07-09 14:51:26.54495-04', 'grabar_operaciones', 'AFTER', 'STATEMENT', 'INSERT', 5);
INSERT INTO cambios VALUES ('2015-07-09 14:51:32.745791-04', 'grabar_operaciones', 'AFTER', 'STATEMENT', 'INSERT', 6);
INSERT INTO cambios VALUES ('2015-07-09 16:21:17.107766-04', 'grabar_operaciones', 'AFTER', 'STATEMENT', 'UPDATE', 7);
INSERT INTO cambios VALUES ('2015-07-09 16:30:49.583838-04', 'grabar_operaciones', 'AFTER', 'STATEMENT', 'UPDATE', 8);
INSERT INTO cambios VALUES ('2015-07-09 16:30:50.727819-04', 'grabar_operaciones', 'AFTER', 'STATEMENT', 'UPDATE', 9);
INSERT INTO cambios VALUES ('2015-07-09 16:30:51.239964-04', 'grabar_operaciones', 'AFTER', 'STATEMENT', 'UPDATE', 10);
INSERT INTO cambios VALUES ('2015-07-09 16:30:51.735574-04', 'grabar_operaciones', 'AFTER', 'STATEMENT', 'UPDATE', 11);
INSERT INTO cambios VALUES ('2015-07-09 16:30:52.71925-04', 'grabar_operaciones', 'AFTER', 'STATEMENT', 'UPDATE', 12);
INSERT INTO cambios VALUES ('2015-07-09 16:41:19.540283-04', 'grabar_operaciones', 'AFTER', 'STATEMENT', 'INSERT', 13);


--
-- TOC entry 1901 (class 0 OID 0)
-- Dependencies: 166
-- Name: cambios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

--SELECT pg_catalog.setval('cambios_id_seq', 13, true);


--
-- TOC entry 1885 (class 0 OID 17354)
-- Dependencies: 163 1889
-- Data for Name: person; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO person VALUES (1, 'tata', 12, '11', NULL);
INSERT INTO person VALUES (2, 'viejo', 15, '1', NULL);
INSERT INTO person VALUES (3, 'matasssssssssq', 22, '1', NULL);
INSERT INTO person VALUES (4, 'assd', 22, '1', NULL);
INSERT INTO person VALUES (5, 'wewqwqw', 1, '1', NULL);
INSERT INTO person VALUES (6, 'hgvhfvg', 54, 'server1', NULL);


--
-- TOC entry 1902 (class 0 OID 0)
-- Dependencies: 162
-- Name: person_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

--SELECT pg_catalog.setval('person_id_seq', 6, true);


--
-- TOC entry 1887 (class 0 OID 17370)
-- Dependencies: 165 1889
-- Data for Name: server; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO server VALUES (1, 'tastico');
INSERT INTO server VALUES (2, 'mios');
INSERT INTO server VALUES (698, 'person');
INSERT INTO server VALUES (699, 'person');
INSERT INTO server VALUES (700, 'person');
INSERT INTO server VALUES (701, 'person');
INSERT INTO server VALUES (702, 'person');
INSERT INTO server VALUES (703, 'person');
INSERT INTO server VALUES (704, 'person');
INSERT INTO server VALUES (705, 'person');
INSERT INTO server VALUES (706, 'person');
INSERT INTO server VALUES (707, 'person');


--
-- TOC entry 1903 (class 0 OID 0)
-- Dependencies: 164
-- Name: server_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

--SELECT pg_catalog.setval('server_id_seq', 707, true);


--
-- TOC entry 1774 (class 2606 OID 17399)
-- Dependencies: 161 161 161 1890
-- Name: cambios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

--ALTER TABLE ONLY cambios ADD CONSTRAINT cambios_pkey PRIMARY KEY (id, comando);


--
-- TOC entry 1776 (class 2606 OID 17401)
-- Dependencies: 163 163 1890
-- Name: person_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

--ALTER TABLE ONLY person ADD CONSTRAINT person_pkey PRIMARY KEY (id);


--
-- TOC entry 1778 (class 2606 OID 17378)
-- Dependencies: 165 165 1890
-- Name: server_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

--ALTER TABLE ONLY server  ADD CONSTRAINT server_pkey PRIMARY KEY (id);


--ALTER TABLE ONLY person ADD CONSTRAINT person_server_fkey FOREIGN KEY (server) REFERENCES server(id);

