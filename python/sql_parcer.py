import sql_data_gen as gen
import mysql.connector as sql

from mysql.connector import Error
from time import sleep

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


def query_ADD_FUNDS_TO_CLIENTS(mysql_instance, amount):
    cursor_handler = mysql_instance.cursor()

    cur_table = 'client'
    cur_column = cur_table + tables[cur_table][5]

    query = 'UPDATE ' + cur_table + \
              ' SET ' + cur_column + ' = ' + cur_column + ' + %s'
    args = (amount,)
    try:
        cursor_handler.execute(query, args)
        mysql_instance.commit()
    finally:
        cursor_handler.close()


def fill_query_list(table, columns, rownum):
    query_list = []
    for row in range(rownum):
        query_list.append(tuple(
            table[column][row]
            for column in columns
        ))
    return query_list

def fill_clients(conn):
    table_info = gen.tables['client']
    clients = gen.gen_clients(gen.global_rows['client'],
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

    query_text += """VALUES(%s,%s,%s,%s,%s,%s,%s,%s,%s)"""

    columns = table_info[1:]
    query_list = fill_query_list(clients,
                                 columns,
                                 len(clients['Name']))

    query_buf.executemany(query_text, query_list)
    conn.commit()
    print('Successfully filled table `clients`')
    return clients

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

    columns = table_info[1:]
    for i in range(len(services['Title'])):
        query_list.append(tuple(
            services[key][i]
            for key in columns
        ))
    query_buf.executemany(query_text, query_list)
    conn.commit()
    print('Successfully filled table `services`')
    return services


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
    columns = table_info[1:]
    for i in range(len(equip['Desc'])):
        query_list.append(tuple(
            equip[key][i] for key in columns
        ))
    query_buf.executemany(query_text, query_list)
    conn.commit()
    print('Successfully filled table `equip`')
    return equip

def fill_techitians(conn):
    table_info = gen.tables['technitian']
    technitians = gen.gen_technitian_data(gen.global_rows['technitian'],
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

    columns = table_info[1:]
    for i in range(len(technitians['Name'])):
        query_list.append(tuple(
            technitians[key][i]
            for key in columns
        ))
    print(query_list)
    query_buf.executemany(query_text, query_list)
    conn.commit()
    print('Successfully filled table `technitian`')
    return technitians


def fill_orders(conn,
                clients,
                services,
                equip,
                technitians):
    table_info = gen.tables['order']
    orders = gen.gen_orders(clients,
                            services,
                            equip,
                            technitians)

    print('lol')
    query_buf = conn.cursor()
    query_text = """INSERT INTO `order`("""
    for i in range(1, len(table_info)):
        query_text += """order"""
        query_text += table_info[i]
        if i == (len(table_info) - 1):
            query_text += """) """
        else:
            query_text += """, """

    query_text += """ VALUES(%s,%s,%s,%s,%s,%s,%s)"""
    query_list = []

    columns = table_info[1:]
    for i in range(len(orders['Cost'])):
        query_list.append(tuple(
            orders[key][i]
            for key in columns
        ))

    print(query_list)
    query_buf.execute(query_text, query_list)
    conn.commit()


def truncate_clients(conn):
    query_buf = conn.cursor
    query_buf.execute("TRUNCATE `order`")
    conn.commit()
    query_buf.execute("DELETE FROM `client` WHERE clientID < 100000")
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
    #truncate_clients(conn)
    clients = fill_clients(conn)
    services = fill_services(conn)
    equip = fill_equip(conn)
    technitians = fill_techitians(conn)
    gen.gen_orders(clients,
                   services,
                   equip,
                   technitians)
    '''
    orders = fill_orders(conn,
                         clients,
                         services,
                         equip,
                         technitians)
    '''


'''
for i in range(len(orders['Type'])):
    info = ''
    info += str(orders['Type'][i])
    for j in range(10 - len(info)):
        info += ' '
    info += str(orders['ClientID'][i])
    for j in range(20 - len(info)):
        info += ' '
    info += str(orders['ItemID'][i])
    for j in range(30 - len(info)):
        info += ' '
    info += str(orders['Cost'][i])
    for j in range(40 - len(info)):
        info += ' '
    info += str(orders['Amount'][i])
    for j in range(50 - len(info)):
        info += ' '
    if (orders['TechnitianID'][i]):
        info += str(orders['TechnitianID'][i])
    for j in range(60 - len(info)):
        info += ' '
    info += orders['Date'][i]
    info += '\t'
    print(info)
'''
