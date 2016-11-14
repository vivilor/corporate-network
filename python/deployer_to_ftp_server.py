import ftplib
import aggregator
import codecs


def upload(ftp_handler, cur_project, file):
    ext = aggregator.os.path.splitext(file)[1]
    if ext in cur_project.binary_ext:
        ftp_handler.storbinary('STOR ' + file, open(file, 'rb'), 1024)
    else:
        ftp_handler.storlines('STOR ' + file, codecs.open(file, 'r', 'utf-8'))


def sync_working_dir(ftp_handler, dir):
    ftp_handler.cwd(dir)
    aggregator.os.chdir(dir)


def make_subdirs(ftp_handler, dirs):
    for dir in dirs:
        try:
            ftp_handler.mkd(dir)
            sync_working_dir(ftp_handler, dir)
        except:
            sync_working_dir(ftp_handler, dir)


def deploy_project_to_ftp_server(ftp_handler, cur_project, proj_hierarchy):
    print("Starting deploy process...")
    for directory in proj_hierarchy:
        make_subdirs(ftp_handler, directory.get_subdirs_from_path())
        for file in directory.files:
            print(directory.path + file)
            upload(ftp_handler, cur_project, file)
        ftp_handler.cwd(root_dir)
        aggregator.os.chdir(cur_project.path)


user_data = {
    'host': 'corporate-network.16mb.com',
    'user': 'u108234672',
    'passwd': '&5xHyvc^C$mJF^bD',
    'acct': 'vivilor'
}

if __name__ == '__main__':
    cur_project = aggregator.project()

    print("Connecting to server: ftp://" + user_data['host'])
    print("User: " + user_data['user'] + ' > ' + user_data['acct'])

    ftp_handler = ftplib.FTP(host=user_data['host'],
                             user=user_data['user'],
                             passwd=user_data['passwd'],
                             acct=user_data['acct'])
    hierarchy = aggregator.get_project_hierarchy(cur_project)
    root_dir = ftp_handler.pwd()
    aggregator.print_hierarchy_summary(hierarchy)
    deploy_project_to_ftp_server(ftp_handler, cur_project, hierarchy)
    ftp_handler.close()
