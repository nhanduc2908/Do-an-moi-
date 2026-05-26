import 'package:json_annotation/json_annotation.dart';
import 'package:freezed_annotation/freezed_annotation.dart';
import 'package:hive/hive.dart';

part 'sync_queue_model.freezed.dart';
part 'sync_queue_model.g.dart';

@freezed
@HiveType(typeId: 0)
class SyncQueueModel with _$SyncQueueModel {
  const factory SyncQueueModel({
    @HiveField(0) String? id,
    @HiveField(1) String? endpoint,
    @HiveField(2) String? method,
    @HiveField(3) dynamic data,
    @HiveField(4) Map<String, dynamic>? headers,
    @HiveField(5) @JsonKey(name: 'created_at') DateTime? createdAt,
    @HiveField(6) String? status,
    @HiveField(7) @JsonKey(name: 'retry_count') int? retryCount,
    @HiveField(8) String? error,
    @HiveField(9) @JsonKey(name: 'updated_at') DateTime? updatedAt,
  }) = _SyncQueueModel;

  factory SyncQueueModel.fromJson(Map<String, dynamic> json) => _$SyncQueueModelFromJson(json);
  
  const SyncQueueModel._();
  
  bool get isPending => status == 'pending';
  bool get isSyncing => status == 'syncing';
  bool get isSuccess => status == 'success';
  bool get isFailed => status == 'failed';
  
  bool get canRetry => (retryCount ?? 0) < 3;
}