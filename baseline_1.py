import pandas as pd
from flash.data import labels_from_categorical_csv
from flash.vision import ImageClassificationData
import os
from flash.vision import ImageClassifier
from flash import Trainer
from pytorch_lightning.callbacks.early_stopping import EarlyStopping
from argparse import ArgumentParser
import pytorch_lightning as pl

pl.seed_everything(1234)


def train(root, args):
    # data
    print('loading data')
    data, columns = get_data(root)
    print('loaded data')
    
    # train
    clf = ImageClassifier(backbone='resnet50', num_classes=len(columns))
    trainer = Trainer(
        gpus=args.gpus, 
        callbacks=[EarlyStopping(monitor='val_cross_entropy')]
    )
    trainer.finetune(clf, data, strategy='no_freeze')

    # predict
    preds = manual_predictions(root, clf)
    write_predictions(preds, root, columns)


def get_data(root):
    columns = [
        'ETT - Abnormal',
        'ETT - Borderline',
        'ETT - Normal',
        'NGT - Abnormal',
        'NGT - Borderline',
        'NGT - Incompletely Imaged',
        'NGT - Normal',
        'CVC - Abnormal',
        'CVC - Borderline',
        'CVC - Normal',
        'Swan Ganz Catheter Present'
    ]

    # generate labels from train.csv
    train_labels = labels_from_categorical_csv(
        os.path.join(root, 'train.csv'),
        'StudyInstanceUID', 
        feature_cols=columns
    )

    # get train dataloader
    data = ImageClassificationData.from_filepaths(
        batch_size=32,
        train_filepaths=os.path.join(root, 'train'),
        train_labels=train_labels,
        valid_split=0.10,
    )

    print('train samples:', len(data.train_dataloader().dataset))
    print('valid samples:', len(data.val_dataloader().dataset))
    return data, columns


def manual_predictions(root, model):
    """
    temporary until PL has predict... this is batched to not run out of memory
    """

    # list of files to predict
    test_path = os.path.join(root, 'test')
    test_files = os.listdir(test_path)
    test_files = [os.path.join(test_path, x) for x in test_files]

    # make the predictions in batches
    preds = []
    batch_size = 16
    for i in range(0, len(test_files), batch_size):
        end_i = min(i + batch_size, len(test_files))
        batch_file_paths = test_files[i: end_i]

        batch_preds = clf.predict(batch_file_paths)
        preds.extend(batch_preds)
    return preds


def write_predictions(preds, root, columns):
    # read the names of the test files
    test_file_dir = os.path.join(root, 'test')
    test_file_names = os.listdir(test_file_dir)
    test_file_names = [os.path.splitext(x)[0] for x in test_file_names]

    pred_csv = pd.DataFrame(test_file_names, columns=['StudyInstanceUID'])
    pred_csv[columns] = 0
    names = [columns[x] for x in preds]

    for i in pred_csv.index:
        col = names[i]
        pred_csv.at[i, col] = 1

    pred_csv.to_csv(os.path.join(root, 'submission.csv'), index=False)


if __name__ == '__main__':
    parser = ArgumentParser()
    parser.add_argument('--data_dir', type=str, default=os.getcwd())
    parser.add_argument('--gpus', type=int, default=None)
    args = parser.parse_args()

    train(args.data_dir, args)

