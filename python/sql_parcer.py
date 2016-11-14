import sql_data_gen as gen
import mysql.connector as sql

from mysql.connector import Error


def establish_connection(host, port, database, user, password):
    conn = None
    try:
        conn = sql.connect(host=host,
                           port=port,
                           database=database,
                           user=user,
                           password=password)
        if conn.is_connected():
            print('Connection established')
        else:
            print('Failed to connect')

    except Error as e:
        print(e)

    finally:
        return conn


def fill_clients(conn):
    table_info = gen.tables['client']
    clients = gen.gen_clients_data(10000,
                                   gen.a,
                                   gen.b,
                                   gen.start_date,
                                   199,
                                   5)
    query_buf = conn.cursor()
    query_text = """INSERT INTO client("""
    for i in range(1, len(table_info)):
        query_text += """client"""
        query_text += table_info[i]
        if i == (len(table_info)-1):
            query_text += """) """
        else:
            query_text += """, """

    query_text += """VALUES(%s,%s,%s,%s,%s,%s,%s,%s)"""
    query_list = []
    for i in range(len(clients['Name'])):
        query_list.append((
           clients['Name'][i],
           clients['Birthday'][i].SQLformat(),
           clients['Passport'][i],
           clients['ServicingStart'][i].SQLformat(),
           clients['Funds'][i],
           clients['FundsSetDate'][i].SQLformat(),
           clients['Status'][i],
           clients['StatusSetDate'][i].SQLformat()
        ))
    print(query_list)
    query_buf.executemany(query_text, query_list)
    conn.commit()
    print('Successfully added 10000 entries in db clients')


def fill_services(conn):
    table_info = gen.tables['service']
    services = gen.gen_service_data()


    query_buf = conn.cursor()
    query_text = """INSERT INTO service("""
    for i in range(1, len(table_info)):
        query_text += """service"""
        query_text += table_info[i]
        if i == (len(table_info) - 1):
            query_text += """) """
        else:
            query_text += """, """
    query_text += """VALUES(%s,%s)"""
    query_list = []
    for i in range(len(services['Title'])):
        query_list.append((
            services['Title'][i],
            services['Cost'][i]
        ))
    query_buf.executemany(query_text, query_list)
    conn.commit()


def fill_equip(conn):
    table_info = gen.tables['equip']
    equip = gen.gen_equip_data()

    query_buf = conn.cursor()
    query_text = """INSERT INTO equip("""
    for i in range(1, len(table_info)):
        query_text += """equip"""
        query_text += table_info[i]
        if i == (len(table_info) - 1):
            query_text += """) """
        else:
            query_text += """, """

    query_text += """VALUES(%s,%s)"""
    query_list = []
    for i in range(len(equip['Desc'])):
        query_list.append((
            equip['Desc'][i],
            equip['Cost'][i]
        ))
    query_buf.executemany(query_text, query_list)
    conn.commit()

def fill_techitians(conn):
    table_info = gen.tables['technitian']
    technitians = gen.gen_technitian_data(50,
                                          30000)

    query_buf = conn.cursor()
    query_text = """INSERT INTO technitian("""
    for i in range(1, len(table_info)):
        query_text += """technitian"""
        query_text += table_info[i]
        if i == (len(table_info) - 1):
            query_text += """) """
        else:
            query_text += """, """

    query_text += """VALUES(%s,%s)"""
    query_list = []
    for i in range(len(technitians['Name'])):
        query_list.append((
            technitians['Name'][i],
            technitians['Salary'][i]
        ))
    for row in query_list:
        print(row)
    query_buf.executemany(query_text, query_list)
    conn.commit()


def fill_relations(conn):
    order_info = gen.tables['order']
    csr_info = gen.tables['client_service_relation']
    cerr_info = gen.tables['client_equip_relation']

    relations = gen.gen_(50,
                                          30000)

    query_buf = conn.cursor()
    query_text = """INSERT INTO technitian("""
    for i in range(1, len(order_info)):
        query_text += """order"""
        query_text += table_info[i]
        if i == (len(table_info) - 1):
            query_text += """) """
        else:
            query_text += """, """

    query_text += """VALUES(%s,%s)"""
    query_list = []
    for i in range(len(technitians['Name'])):
        query_list.append((
            technitians['Name'][i],
            technitians['Salary'][i]
        ))
    for row in query_list:
        print(row)
    query_buf.executemany(query_text, query_list)
    conn.commit()


def show_clients(conn):
    cursor_buffer = conn.cursor()
    cursor_buffer.execute("SELECT * FROM client")

    row = cursor_buffer.fetchone()
    while row is not None:
        print(row)
        row = cursor_buffer.fetchone()


if __name__ == '__main__':
    host = 'localhost'
    port = '3306'
    database = 'cloudware'
    user = 'root'
    password = ''

    conn = establish_connection(host, port,
                                database,
                                user, password)
    #fill_clients(conn)
    #fill_services(conn)
    #fill_equip(conn)
    #fill_techitians(conn)
    fill_


