import 'package:json_annotation/json_annotation.dart';
import 'package:freezed_annotation/freezed_annotation.dart';

part 'assessment_model.freezed.dart';
part 'assessment_model.g.dart';

@freezed
class AssessmentModel with _$AssessmentModel {
  const factory AssessmentModel({
    String? id,
    @JsonKey(name: 'assessment_type') String? assessmentType,
    @JsonKey(name: 'target_system_id') String? targetSystemId,
    String? status,
    int? progress,
    double? score,
    @JsonKey(name: 'risk_level') String? riskLevel,
    dynamic scope,
    dynamic findings,
    dynamic recommendations,
    @JsonKey(name: 'started_at') DateTime? startedAt,
    @JsonKey(name: 'completed_at') DateTime? completedAt,
    @JsonKey(name: 'due_date') DateTime? dueDate,
    @JsonKey(name: 'assigned_to') String? assignedTo,
    @JsonKey(name: 'created_at') DateTime? createdAt,
  }) = _AssessmentModel;

  factory AssessmentModel.fromJson(Map<String, dynamic> json) => _$AssessmentModelFromJson(json);
}

extension AssessmentModelX on AssessmentModel {
  bool get isCompleted => status == 'completed';
  bool get isInProgress => status == 'in_progress';
  bool get isDraft => status == 'draft';
  bool get isSubmitted => status == 'submitted';
  
  String get scoreDisplay => score != null ? '${score!.toStringAsFixed(1)}%' : 'N/A';
  
  String get riskLevelDisplay {
    switch (riskLevel?.toLowerCase()) {
      case 'critical': return 'Nghiêm trọng';
      case 'high': return 'Cao';
      case 'medium': return 'Trung bình';
      case 'low': return 'Thấp';
      default: return 'Không xác định';
    }
  }
  
  Color get riskColor {
    switch (riskLevel?.toLowerCase()) {
      case 'critical': return Colors.red;
      case 'high': return Colors.orange;
      case 'medium': return Colors.yellow;
      case 'low': return Colors.green;
      default: return Colors.grey;
    }
  }
}