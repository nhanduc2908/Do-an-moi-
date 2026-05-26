import 'package:json_annotation/json_annotation.dart';
import 'package:freezed_annotation/freezed_annotation.dart';

part 'notification_model.freezed.dart';
part 'notification_model.g.dart';

@freezed
class NotificationModel with _$NotificationModel {
  const factory NotificationModel({
    String? id,
    String? type,
    String? title,
    String? message,
    dynamic data,
    @JsonKey(name: 'read_at') DateTime? readAt,
    String? priority,
    @JsonKey(name: 'sender_id') String? senderId,
    @JsonKey(name: 'created_at') DateTime? createdAt,
  }) = _NotificationModel;

  factory NotificationModel.fromJson(Map<String, dynamic> json) => _$NotificationModelFromJson(json);
}

extension NotificationModelX on NotificationModel {
  bool get isRead => readAt != null;
  
  String get priorityDisplay {
    switch (priority) {
      case 'critical': return 'Nghiêm trọng';
      case 'high': return 'Cao';
      case 'medium': return 'Trung bình';
      case 'low': return 'Thấp';
      default: return priority ?? 'Normal';
    }
  }
  
  Color get priorityColor {
    switch (priority) {
      case 'critical': return Colors.red;
      case 'high': return Colors.orange;
      case 'medium': return Colors.yellow;
      case 'low': return Colors.green;
      default: return Colors.blue;
    }
  }
}