import 'package:sqflite/sqflite.dart';
import 'package:path/path.dart';
import '../../../core/utils/logger.dart';

class DatabaseHelper {
  static final DatabaseHelper _instance = DatabaseHelper._internal();
  static Database? _database;

  DatabaseHelper._internal();

  factory DatabaseHelper() => _instance;

  Future<Database> get database async {
    if (_database != null) return _database!;
    _database = await _initDatabase();
    return _database!;
  }

  Future<Database> _initDatabase() async {
    final dbPath = await getDatabasesPath();
    final path = join(dbPath, 'security_app.db');
    
    Logger.database('Initializing database at: $path');
    
    return await openDatabase(
      path,
      version: 1,
      onCreate: _onCreate,
      onUpgrade: _onUpgrade,
    );
  }

  Future<void> _onCreate(Database db, int version) async {
    Logger.database('Creating database tables');
    
    await db.execute('''
      CREATE TABLE IF NOT EXISTS assessments (
        id TEXT PRIMARY KEY,
        title TEXT,
        status TEXT,
        score REAL,
        created_at TEXT
      )
    ''');
    
    await db.execute('''
      CREATE TABLE IF NOT EXISTS incidents (
        id TEXT PRIMARY KEY,
        title TEXT,
        severity TEXT,
        status TEXT,
        created_at TEXT
      )
    ''');
    
    await db.execute('''
      CREATE TABLE IF NOT EXISTS vulnerabilities (
        id TEXT PRIMARY KEY,
        cve_id TEXT,
        title TEXT,
        severity TEXT,
        created_at TEXT
      )
    ''');
  }

  Future<void> _onUpgrade(Database db, int oldVersion, int newVersion) async {
    Logger.database('Upgrading database from v$oldVersion to v$newVersion');
    
    for (int version = oldVersion + 1; version <= newVersion; version++) {
      await _upgradeToVersion(db, version);
    }
  }

  Future<void> _upgradeToVersion(Database db, int version) async {
    switch (version) {
      case 2:
        await db.execute('ALTER TABLE assessments ADD COLUMN updated_at TEXT');
        break;
    }
  }

  Future<void> insert(String table, Map<String, dynamic> data) async {
    final db = await database;
    await db.insert(table, data, conflictAlgorithm: ConflictAlgorithm.replace);
    Logger.database('Inserted into $table');
  }

  Future<List<Map<String, dynamic>>> query(
    String table, {
    String? where,
    List<dynamic>? whereArgs,
    String? orderBy,
    int? limit,
  }) async {
    final db = await database;
    return await db.query(
      table,
      where: where,
      whereArgs: whereArgs,
      orderBy: orderBy,
      limit: limit,
    );
  }

  Future<int> update(
    String table,
    Map<String, dynamic> data, {
    required String where,
    required List<dynamic> whereArgs,
  }) async {
    final db = await database;
    return await db.update(table, data, where: where, whereArgs: whereArgs);
  }

  Future<int> delete(
    String table, {
    required String where,
    required List<dynamic> whereArgs,
  }) async {
    final db = await database;
    return await db.delete(table, where: where, whereArgs: whereArgs);
  }

  Future<void> close() async {
    final db = await database;
    await db.close();
    _database = null;
    Logger.database('Database closed');
  }
}