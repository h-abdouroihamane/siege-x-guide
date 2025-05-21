import os, requests, unidecode

from bs4 import BeautifulSoup as bs
url = 'https://www.ubisoft.com/en-gb/game/rainbow-six/siege/game-info/operators'
r = requests.get(url)
soup = bs(r.content, 'html.parser')

def parse_node(node):
    card_node = node.select('.oplist__card__img')[0]
    operator_name = unidecode.unidecode(card_node['alt']).lower()
    operator_card_src = card_node['src']

    icon_node = node.select('.oplist__card__icon')[0]
    operator_icon_src = icon_node['src']

    return {
        'operator_name': operator_name,
        'operator_card': operator_card_src,
        'operator_icon': operator_icon_src
    }

def get_operators_images():
    return [parse_node(node) for node in soup.select('.oplist__card')]


def downloadImage(img_src: str, filename: str, destination_folder: str):
    if not os.path.isdir(destination_folder):
        os.mkdir(destination_folder)

    response = requests.get(img_src)
    file_path = os.path.join(destination_folder, filename)

    with open(file_path, mode="wb") as f:
        f.write(response.content)

    print(file_path)

    return


def download_operator_images(operator_list):
    for operator in operator_list:
        filename = f'{operator['operator_name']}.png'
        downloadImage(operator['operator_card'], filename, 'operatorCards')
        downloadImage(operator['operator_icon'], filename, 'operatorIcons')
    return

if __name__ == "__main__":
    imgs = get_operators_images()
    download_operator_images(imgs)
